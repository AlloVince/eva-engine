<?php
namespace Epic\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;
use Core\Auth;
use Album\Form;

class AlbumController extends ActionController
{
    protected $album;
    
    protected $post;
    
    protected $eventData;
   
    public function indexAction()
    {
        return $this->listAction();
    }

    public function removeAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData = $this->params()->fromPost();
            $callback = $this->params()->fromPost('callback');

            $form = new \Album\Form\AlbumDeleteForm();
            $form->bind($postData);
            if ($form->isValid()) {

                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Album\Model\Album');
                $itemModel->setItem($postData)->removeAlbum();
                $callback = $callback ? $callback : '/my/album/';
                $this->redirect()->toUrl($callback);

            } else {
                return array(
                    'post' => $postData,
                );
            }

        } else {
            $id = $this->params('id');
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $item = $itemModel->getAlbum($id)->toArray();

            return array(
                'callback' => $this->params()->fromQuery('callback'),
                'item' => $item,
            );

        }

    }

    public function createAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;
        }

        $postData = $request->getPost();
        $callback = $this->params()->fromPost('callback');
        $form = new \Epic\Form\AlbumCreateForm();
        $form->useSubFormGroup()
            ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $albumId = $itemModel->setItem($postData)->createAlbum();
            $callback = $callback ? $callback : '/albums/edit/' . $albumId;
            $this->redirect()->toUrl($callback);
        } else {
           
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/album/create');
        if ($request->isPost()) {
            $postData = $request->getPost();
            $callback = $this->params()->fromPost('callback');
            $form = new \Epic\Form\AlbumEditForm();
            $form->useSubFormGroup()
                ->bind($postData);
            
            if ($form->isValid()) {
                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Album\Model\Album');
                $albumId = $itemModel->setItem($postData)->saveAlbum();
                $callback = $callback ? $callback : '/albums/edit/' . $albumId;
                $this->redirect()->toUrl($callback);

            } else {
            }

            $viewModel->setVariables(array(
                'form' => $form,
                'item' => $postData,
            ));
        } else {
            $id = $this->params('id');
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $item = $itemModel->getAlbum($id, array(
                'self' => array(
                    '*',
                ),
                'join' => array(
                    'File' => array(
                        'self' => array(
                            '*',
                            'getThumb()',
                        )
                    ),
                    'Category' => array(
                        '*'
                    ),
                ),
            ));
            if(isset($item['AlbumFile'][0])){
                $item['AlbumFile'] = $item['AlbumFile'][0];
            }

            $viewModel->setVariables(array(
                'item' => $item,
            ));
        }

        return $viewModel;
    }


    public function uploadAction()
    {

        $this->changeViewModel('json');
        $this->getServiceLocator()->get('Application')->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($event){
            $event->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'text/plain');
        }, -10000);

        $postData = $this->params()->fromPost();
        $form = new Form\UploadForm();
        $form->useSubFormGroup()
            ->bind($postData);

        $itemModel = Api::_()->getModel('Album\Model\Upload');

        $response = array();
        if ($form->isValid() && $form->getFileTransfer()->isUploaded()) {
            $item = $form->getData();
            if($form->getFileTransfer()->receive()){
                if ($this->album) {
                    $itemModel->setAlbum($this->album);
                } else {
                    $albumModel = Api::_()->getModel('Album\Model\Album');
                    $album = $albumModel->getAlbum($item['AlbumFile']['album_id']);
                    $itemModel->setAlbum($album);
                    $this->album = $album;
                }

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
            //p($form->getMessages());
        }

        return new JsonModel($response);
    
    }

    public function photoAction()
    {
        $id = $this->params('id');
        
        $itemModel = Api::_()->getModel('Album\Model\Album');
        $item = $itemModel->getAlbum($id, array(
            'self' => array(
                '*',
            ),
            'proxy' => array(
                'Album\Item\Album::Cover' => array(
                    '*',
                    'getThumb()'
                ),
            ),
            'join' => array(
                'Count' => array(
                    '*'
                ),
            ),
        ));
        
        $itemModel = Api::_()->getModel('Album\Model\AlbumFile');

        $query = array(
            'album_id' => $id,
            'noLimit' => true,
        );
        
        $items = $itemModel->setItemList($query)->getAlbumFileList();
        $items->toArray(array(
            'self' => array(
                '*',
            ),
            'proxy' => array(
                'Album\Item\AlbumFile::Image' => array(
                    '*',
                    'getThumb()'
                ),
            ),
        ));

        $viewModel = new ViewModel();
        $viewModel->setVariables(array(
            'item' => $item,
            'items' => $items,
        ));   
        
        return $viewModel;
    }

    public function coverAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/album/photo');
        
        $id = $this->params('id');
        $fileId = $this->params()->fromQuery('file_id');
        $callback = $this->params()->fromQuery('callback');
        $itemModel = Api::_()->getModel('Album\Model\Album');
        $itemModel->setAlbumCover($id, $fileId);

        if($callback){
            return $this->redirect()->toUrl($callback);
        } 
    }

    public function imageremoveAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData = $this->params()->fromPost();
            $callback = $this->params()->fromPost('callback');

            $form = new \Album\Form\AlbumFileDeleteForm();
            $form->bind($postData);
            if ($form->isValid()) {

                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Album\Model\AlbumFile');
                $itemModel->setItem($postData)->removeAlbumFile();
            
                $albumModel = Api::_()->getModel('Album\Model\Album');
                $album = $albumModel->getAlbum($postData['album_id']);

                if ($album['cover_id'] == $postData['file_id']) {
                    $album->cover_id = null;
                    $album->save();
                }

                if($callback){
                    $this->redirect()->toUrl($callback);
                }

            } else {
                return array(
                    'post' => $postData,
                );
            }

        } else {
            $albumId = $this->params('id');
            $fileId = $this->params()->fromQuery('file_id');

            $itemModel = Api::_()->getModel('Album\Model\AlbumFile');
            $item = $itemModel->getAlbumFile($albumId,$fileId)->toArray();

            return array(
                'callback' => $this->params()->fromQuery('callback'),
                'item' => $item,
                'album' => array('id' => $albumId),
            );
        }

    }
}
