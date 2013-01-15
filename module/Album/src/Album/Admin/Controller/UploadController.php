<?php
namespace Album\Admin\Controller;

use Album\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;


class UploadController extends RestfulModuleController
{
    protected $renders = array(
        'restPostUpload' => 'upload/get',    
    );

    public function restGetUpload()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Album\Model\Album');
        $item = $itemModel->getAlbum($id, array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                    ),
                ),
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
                'CategoryAlbum' => array(
                    'self' => array(
                        '*',
                    )
                ),
                'Category' => array(
                    'self' => array(
                        '*',
                    )
                ),
            ),
        ));

        if(isset($item['AlbumFile'][0])){
            $item['AlbumFile'] = $item['AlbumFile'][0];
        }

        return array(
            'item' => $item,
        );
    }

    public function restPostUpload()
    {
        /*
        $this->getServiceLocator()->get('Application')->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($event){
            $event->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'text/plain');
        }, -10000);
        */

        $postData = $this->params()->fromPost();
        $form = new Form\UploadForm();
        $form->useSubFormGroup()
            ->bind($postData);

        $itemModel = Api::_()->getModel('Album\Model\Upload');

        $response = array();
        if ($form->isValid() && $form->getFileTransfer()->isUploaded()) {
            $item = $form->getData();
            if($form->getFileTransfer()->receive()){
                $itemModel->setAlbum(array(
                    'id' => $item['AlbumFile']['album_id']
                ));

                $files = $form->getFileTransfer()->getFileInfo();
                $itemModel->setUploadFiles($files);
                $itemModel->setConfigKey('default')->createFiles();
                $lastFileId = $itemModel->getLastFileId();

                if($lastFileId) {
                    $item = $itemModel->getFile($lastFileId, array(
                        'self' => array(
                            '*',
                            'getUrl()',
                            'getThumb()',
                        ),
                    ));
                    $file = array(
                        'id' => $item['id'],
                        'name' => $item['originalName'],
                        'size' => (int)$item['fileSize'],
                        'url' => $item['Url'],
                        'thumbnail_url' => $item['Thumb'],
                        'delete_type' => 'DELETE',
                        'delete_url' => '/admin/album/upload/remove/' . $item['id']
                    );
                    $response = array(
                        $file
                    );
                }
            }
        } else {
            p($form->getMessages());
        }

        return array(
            'item' => array('id' => $this->params('id')),
            'form' => $form
        );

        return new JsonModel($response);
    }

    public function restDeleteUpload()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\AlbumDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $itemModel->setItem($postData)->removeAlbum();

            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'post' => $postData,
            );
        }
    }
}
