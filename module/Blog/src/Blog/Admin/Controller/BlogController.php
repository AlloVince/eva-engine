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
        $page = $request->query()->get('page', 1);

        $postModel = Api::_()->getModel('Blog\Model\Post');
        $postTable = $postModel->getItemTable();
        $posts = $postTable->enableCount()->order('id DESC')->page($page)->find('all');
        $paginator = $postModel->getPaginator();
        return array(
            'page' => $request->query()->get('page', 1),
            'posts' => $posts->toArray(),
            'paginator' => $paginator,
        );
    }

    public function restGetBlog()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $postModel = Api::_()->getModel('Blog\Model\Post');
        $postTable = $postModel->getItemTable();
        $postinfo = $postTable->find($id);
        $postinfo = $postModel->getItemArray($postinfo);
        return array(
            'post' => $postinfo,
        );
    }

    public function restPostBlog()
    {
        $request = $this->getRequest();
        $postData = $request->post();
        $form = new Form\PostForm();

        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();

            $postModel = Api::_()->getModel('Blog\Model\Post');
            $postTable = $postModel->getItemTable();
            $postData = $postModel->getItemArray($postData);

            $postData = $form->fieldsMap($postData, true);
            $postTable->create($postData);
            $postId = $postTable->getLastInsertValue();

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
        $postData = $request->post();
        $form = new Form\PostForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
            $postData = $form->fieldsMap($postData, true);
            $postTable->where("id = {$postData['id']}")->save($postData);

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
        $postData = $request->post();
        $callback = $request->post()->get('callback');

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
