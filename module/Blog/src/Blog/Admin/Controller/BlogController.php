<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{
    protected $renders = array(
        'restPutBlog' => 'blog/get',    
        'restPostBlog' => 'blog/get',    
        'restDeleteBlog' => 'remove/get',    
    );

    public function restIndexBlog()
    {
        $query = $this->getRequest()->getQuery();

        $form = new Form\PostSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'posts' => array(),
            );
        }

        $itemModel = Api::_()->getModelService('Blog\Model\Post');
        $items = $itemModel->setItemList($query)->getPostList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetBlog()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModelService('Blog\Model\Post');
        $item = $itemModel->getPost($id, array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                        'getContentHtml()',
                    ),
                ),
                'Categories' => array(
                
                )
            )
        ));

        return array(
            'item' => $item,
        );
    }

    public function restPostBlog()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\PostCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('Blog\Model\Post');
            $postId = $itemModel->setItem($postData)->createPost();
            $this->flashMessenger()->addMessage('post-create-succeed');
            $this->redirect()->toUrl('/admin/blog/' . $postId);

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

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('Blog\Model\Post');
            $postId = $itemModel->setItem($postData)->savePost();

            $this->flashMessenger()->addMessage('post-edit-succeed');
            $this->redirect()->toUrl('/admin/blog/' . $postData['id']);

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
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('Blog\Model\Post');
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
