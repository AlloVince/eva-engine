<?php
namespace File\Api\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Zend\View\Model\JsonModel;

class FileController extends RestfulModuleController
{
    public function restIndexFile()
    {
        $query = $this->getRequest()->getQuery();

        $form = new Form\FileSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        }

        $itemModel = Api::_()->getModel('File\Model\File');
        $items = $itemModel->setItemList($query)->getFileList(array(
            'self' => array(
                '*',
                'getUrl()',
                'getThumb()',
                'getReadableFileSize()',
            ),
        ));
        $paginator = $itemModel->getPaginator();

        $this->layout('layout/adminblank');

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restPostFile()
    {
        $this->getServiceLocator()->get('Application')->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($event){
            $event->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'text/plain');
        }, -10000);

        $postData = $this->params()->fromPost();
        $form = new Form\UploadForm();
        $form->bind($postData);

        $itemModel = Api::_()->getModel('File\Model\File');

        $response = array();
        if ($form->isValid() && $form->getFileTransfer()->isUploaded()) {
            if($form->getFileTransfer()->receive()){
                
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
                        'delete_url' => '/api/file/' . $item['id']
                    );
                    $response = array(
                        $file
                    );
                }
            }
        } else {
        }

        return new JsonModel($response);
    }

    public function restPutFile()
    {
        return $this->restPostFile();
    }

    public function restDeleteFile()
    {
        //Fix ie
        $this->getServiceLocator()->get('Application')->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($event){
            $event->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'text/plain');
        }, -10000);

        $id = $this->params('id');
        $itemModel = Api::_()->getModel('File\Model\File');
        $itemModel->setItem(array(
            'id' => $id
        ))->removeFile();
        return new JsonModel();
    }

}
