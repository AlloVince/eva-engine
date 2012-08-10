<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CategoryController extends RestfulModuleController
{
    protected $addResources = array(
        'create',
        'remove'
    );

    protected $renders = array(
        'restGetCategoryCreate' => 'category/get',    
        'restPostCategory' => 'category/get',    
        'restPutCategory' => 'category/get',    
        'restDeleteCategory' => 'category/remove',    
    ); 

    public function restIndexCategory()
    {
        $request = $this->getRequest();
        
        $query = $request->getQuery();

        $form = Api::_()->getForm('Blog\Form\CategoryForm');
        $selectQuery = $form->fieldsMap($query, true);
       
        /* 
        $categoryModel = Api::_()->getModel('Blog\Model\Category');
        $categories = $categoryModel->setItemListParams($selectQuery)->getCategories();
        $paginator = $categoryModel->getPaginator();
        */
        
        $paginator = null;

        $tree = new \Core\Tree\Tree('BinaryTreeDb',false,
            array('dbTable' => 'Blog\DbTable\Categories')
        );

        $categories = $tree->getTree();

        return array(
            'form' => $form,
            'categories' => $categories,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetCategoryCreate()
    {
    }

    public function restGetCategory()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');

        $categoryDb = Api::_()->getDbTable('Blog\DbTable\Categories');
        $categoryinfo = $categoryDb->where(array('id' => $id))->find('one');
        return array(
            'category' => $categoryinfo,
        );
    }

    public function restGetCategoryRemove()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');

        $categoryDb = Api::_()->getDbTable('Blog\DbTable\Categories');
        $categoryinfo = $categoryDb->where(array('id' => $id))->find('one');
        return array(
            'category' => $categoryinfo,
            'callback' => $this->getRequest()->getQuery()->get('callback'),
        );
    }

    public function restPostCategory()
    {
        $request = $this->getRequest();
        $categoryData = $request->getPost();
        $form = new Form\CategoryForm();
        
        $form->setData($categoryData)->enableFilters();
        if ($form->isValid()) {
<<<<<<< HEAD
            $categoryData = $form->getData();
            $categoryData = $form->fieldsMap($categoryData, true);
            $categoryData['createTime'] = isset($categoryData['createTime']) ? $categoryData['createTime'] : \Eva\Date\Date::getNow();

            $tree = new \Core\Tree\Tree('BinaryTreeDb',false,
                array('dbTable' => 'Blog\DbTable\Categories')
            );

            $categoryId = $tree->insertNode($categoryData);
            
            $this->redirect()->toUrl('/admin/blog/category/' . $categoryId);
=======

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Category');
            $postData = $form->fieldsMap($postData, true);
            $itemId = $itemModel->setItem($postData)->createCategory();
            $this->flashMessenger()->addMessage('category-create-succeed');
            $this->redirect()->toUrl('/admin/blog/category/' . $itemId);
>>>>>>> 8e5c63161f8e4146ec415f935d324455f40096c7

        } else {

            //p($form->getInputFilter()->getInvalidInput());
        }

        return array(
            'form' => $form,
            'category' => $postData,
        );
    }

    public function restPutCategory()
    {
        $request = $this->getRequest();
        $categoryData = $request->getPost();
        $form = new Form\CategoryEditForm();
        $form->init();

        $form->setData($categoryData)->enableFilters();
        if ($form->isValid()) {

            $categoryData = $form->getData();
            $categoryDb = Api::_()->getDbTable('Blog\DbTable\Categories');
            $categoryData = $form->fieldsMap($categoryData, true);
            
            $tree = new \Core\Tree\Tree('BinaryTreeDb',false,
                array('dbTable' => 'Blog\DbTable\Categories')
            );

            $categoryId = $tree->updateNode($categoryData);


            $categoryDb->where(array('id' => $categoryData['id']))->save($categoryData);
            $this->redirect()->toUrl('/admin/blog/category/' . $categoryData['id']);

        } else {

            //p($form->getInputFilter()->getInvalidInput());
        }

        return array(
            'form' => $form,
            'category' => $categoryData,
        );
    }

    public function restDeleteCategory()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\CategoryDeleteForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $categoryData = $form->getData();
            $categoryTable = Api::_()->getDbTable('Blog\DbTable\Categories');

            $categoryData = $categoryTable->where("id = {$categoryData['id']}")->find('one');
            
            $tree = new \Core\Tree\Tree('BinaryTreeDb',false,
                array('dbTable' => 'Blog\DbTable\Categories')
            );

            $tree->deleteNode($categoryData);
            
            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'category' => $categoryData,
            );
        }
    }
}
