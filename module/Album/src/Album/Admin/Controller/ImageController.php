<?php
namespace Album\Admin\Controller;

use Album\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;


class ImageController extends RestfulModuleController
{
    protected $renders = array(
        'restDeleteImage' => 'image/remove',    
        'restGetImageCover' => 'image/get',    
    );

    protected $addResources = array(
        'remove',
        'cover',
    );

    public function restGetImage()
    {
        $query = $this->getRequest()->getQuery();
        $id = $this->params('id');
        $page = $query['page'];

        $itemModel = Api::_()->getModel('Album\Model\Album');
        $album = $itemModel->getAlbum($id);

        $itemModel = Api::_()->getModel('Album\Model\AlbumFile');

        $query = array(
            'album_id' => $id,
            'page' => $page ? $page : 1,
        );
        
        $items = $itemModel->setItemList($query)->getAlbumFileList();
        $paginator = $itemModel->getPaginator();
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
        
        return array(
            'album' => $album,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetImageRemove()
    {
        $albumId = $this->params('id');
        $fileId = $this->params()->fromQuery('file_id');
        $itemModel = Api::_()->getModel('Album\Model\AlbumFile');
        $item = $itemModel->getAlbumFile($albumId,$fileId)->toArray();
        return array(
            'callback' => $this->params()->fromQuery('callback'),
            'item' => $item,
        );
    }

    public function restDeleteImageRemove()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\AlbumFileDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Album\Model\AlbumFile');
            $itemModel->setItem($postData)->removeAlbumFile();

            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'post' => $postData,
            );
        }
    }

    public function restGetImageCover()
    {
        $id = $this->params('id');
        $fileId = $this->params()->fromQuery('file_id');
        $callback = $this->params()->fromQuery('callback');
        $itemModel = Api::_()->getModel('Album\Model\Album');
        $itemModel->setAlbumCover($id, $fileId);
        
        if($callback){
            return $this->redirect()->toUrl($callback);
        }
    }
}
