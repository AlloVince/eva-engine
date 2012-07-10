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
        );
    }

    public function restPostBlog()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\PostForm();

        $subForms = array(
            'Text' => array('Blog\Form\TextForm'),
        );
        $form->setSubforms($subForms)->init();

        $form->setData($postData)->enableFilters();
        if ($form->isValid()) {

            $postData = $form->getData();
            $postModel = Api::_()->getModel('Blog\Model\Post');
            $postData = $form->fieldsMap($postData, true);
            $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->createPost();
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
        $form = new Form\PostForm();
        $subForms = array(
            'Text' => array('Blog\Form\TextForm'),
        );
        $form->setSubforms($subForms)->init();

        $form->setData($postData)->enableFilters();
        if ($form->isValid()) {

            $postData = $form->getData();
            $postModel = Api::_()->getModel('Blog\Model\Post');
            $postData = $form->fieldsMap($postData, true);
            $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->savePost();
            $this->redirect()->toUrl('/admin/blog/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'post' => $postData,
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
