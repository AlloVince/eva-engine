<?php
namespace Blog\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

class BlogController extends RestfulModuleController
{
    protected $renders = array(
        'restPutBlog' => 'blank',    
        'restPostBlog' => 'blank',    
        'restDeleteBlog' => 'blank',    
    );

    public function indexAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $form = new Form\PostSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return new JsonModel(array(
                'form' => $form,
                'items' => array(),
            ));
        }

        $itemModel = Api::_()->getModel('Blog\Model\Post');
        $items = $itemModel->setItemList($query)->getPostList(array(
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                        'getPreview()',
                    )
                ),
            )
        ));
        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator->toArray() : null;

        if(Api::_()->isModuleLoaded('User')){
            $userList = array();
            $userList = $itemModel->getUserList(array(
                'columns' => array(
                    'id',
                    'userName',
                    'email',
                ),
            ))->toArray(array(
                'self' => array(
                    'getEmailHash()',
                ),
            ));
            $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));
        }

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function restPostBlog()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\PostCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);
        
        $callback = $this->params()->fromPost('callback', '/pa/');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $postId = $itemModel->setItem($postData)->createPost();
            $this->flashMessenger()->addMessage('post-create-succeed');
            $this->redirect()->toUrl($callback . $postId);

        } else {
            
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutBlog()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\PostEditForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $flashMesseger = array();

        $callback = $this->params()->fromPost('callback', '/feed/');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $postId = $itemModel->setItem($postData)->savePost();

            $this->flashMessenger()->addMessage('post-edit-succeed');
            $this->redirect()->toUrl($callback . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteBlog()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\PostDeleteForm();
        $form->bind($postData);
        
        $callback = $this->params()->fromPost('callback', '/feed/');
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $itemModel->setItem($postData)->removePost();

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
