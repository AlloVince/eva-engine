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
        $request = $this->getRequest();

        $query = $request->getQuery();

        $form = Api::_()->getForm('Blog\Form\PostSearchForm');
        $selectQuery = $form->fieldsMap($query, true);

        $postModel = Api::_()->getModel('Blog\Model\Post');
        $posts = $postModel->setItemListParams($selectQuery)->getPosts();
        $paginator = $postModel->getPaginator();

        return array(
            'form' => $form,
            'posts' => $posts,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetBlog()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $postModel = Api::_()->getModel('Blog\Model\Post');
        $postinfo = $postModel->setItemParams($id)->getPost();
        return array(
            'post' => $postinfo,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPostBlog()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\PostForm();

        $subForms = array(
            'Text' => array('Blog\Form\TextForm'),
            'CategoryPost' => array('Blog\Form\CategoryPostForm'),
        );
        $form->setSubforms($subForms)->init();

        $form->setData($postData)->enableFilters();
        if ($form->isValid()) {

            $postData = $form->getData();
            $postModel = Api::_()->getModel('Blog\Model\Post');
            $postData = $form->fieldsMap($postData, true);
            $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->createPost();
            $this->flashMessenger()->addMessage('post-create-succeed');
            $this->redirect()->toUrl('/admin/blog/' . $postId);

        } else {
            
            //p($form->getInputFilter()->getInvalidInput());
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutBlog()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\PostEditForm();
        $subForms = array(
            'Text' => array('Blog\Form\TextForm'),
            'CategoryPost' => array('Blog\Form\CategoryPostForm'),
        );

        $form->setSubforms($subForms)
             ->init()
             ->setData($postData)
             ->enableFilters();

        $flashMesseger = array();
        if ($form->isValid()) {
            $postData = $form->getData();
            $postModel = Api::_()->getModel('Blog\Model\Post');
            $postData = $form->fieldsMap($postData, true);
            $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->savePost();
            $this->flashMessenger()->addMessage('post-edit-succeed');
            $this->redirect()->toUrl('/admin/blog/' . $postData['id']);
        } else {
            //$this->flashMessenger()->addMessage('');
            $flashMesseger = array('post-edit-failed');
        }

        return array(
            'form' => $form,
            'post' => $postData,
            'flashMessenger' => $flashMesseger
        );
    }

    public function restDeleteBlog()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\PostDeleteForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $postTable = Api::_()->getDbTable('Blog\DbTable\Posts');

            $postTable->where("id = {$postData['id']}")->remove();

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
