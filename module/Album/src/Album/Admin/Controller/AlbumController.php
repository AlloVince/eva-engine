<?php
namespace Album\Admin\Controller;

use Album\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class AlbumController extends RestfulModuleController
{
    protected $renders = array(
        'restPutAlbum' => 'album/get',    
        'restPostAlbum' => 'album/get',    
        'restDeleteAlbum' => 'remove/get',    
    );

    public function restIndexAlbum()
    {
        $query = $this->getRequest()->getQuery();
        $form = new Form\AlbumSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $itemModel = Api::_()->getModel('Album\Model\Album');
        $items = $itemModel->setItemList($query)->getAlbumList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetAlbum()
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

    public function restPostAlbum()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\AlbumCreateForm();
        $form->useSubFormGroup()
            ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $user = \Core\Auth::getLoginUser('Auth_Admin');
            $postData['user_id'] = $user['id'];
            $albumId = $itemModel->setItem($postData)->createAlbum();
            $this->flashMessenger()->addMessage('album-create-succeed');
            $this->redirect()->toUrl('/admin/album/' . $albumId);

        } else {

        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutAlbum()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\AlbumEditForm();
        $form->useSubFormGroup()
            ->bind($postData);

        $flashMesseger = array();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $albumId = $itemModel->setItem($postData)->saveAlbum();

            $this->flashMessenger()->addMessage('album-edit-succeed');
            $this->redirect()->toUrl('/admin/album/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteAlbum()
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
