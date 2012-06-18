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
        $posts = $postTable->enableCount()->limit(10)->order('id DESC')->page($page)->find('all');
        $paginator = $postModel->getPaginator();

        //$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
        //$posts = $postTable->enableCount()->order('id DESC')->page($page)->find('all');
        //$postCount = $postTable->getCount();


        //setCurrentPageNumber
        //setItemCountPerPage


        //$diConfig = array(
        //    'instance' => array(
        //        'Zend\Paginator\Adapter\DbTableSelect' => array(
        //            'parameters' => array(
        //                '_rowCount' => $postTable->getCount(),
        //                '_select' => $postTable->getSelect()
        //            )
        //        ),
        //        'Eva\Paginator\Paginator' => array(
        //            'parameters' => array(
        //                'rowCount' => $postTable->getCount(),
        //                'adapter' => 'Zend\Paginator\Adapter\DbTableSelect',
        //            ),
        //        ),
        //        'Blog\Model\Post' => array(
        //            'parameters' => array(
        //                'itemTable' => $postTable,
        //                'paginator' => 'Eva\Paginator\Paginator',
        //            ),
        //        ),
        //    )
        //);

        //$postModel = Api::_()->getModel('Blog\Model\Post', $diConfig);
        /*
        p($paginator->getItemCountPerPage());
        p($paginator->getPageRange());
        p($paginator->getPages());
        p($paginator);
        */
        //p($postModel);

        return array(
            'posts' => $posts->toArray(),
            'paginator' => $paginator->toArray(),
        );
    }

    public function restGetBlog()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
        $postinfo = $postTable->find($id);
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

            $postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
            $postData = $form->fieldsMap($postData, true);
            $postTable->create($postData);

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
        $form = new Form\PostDeleteForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $postTable = Api::_()->getDbTable('Blog\DbTable\Posts');

            $postTable->where("id = {$postData['id']}")->remove();

        } else {
            return array(
                'post' => $postData,
            );
        }
    }
}
