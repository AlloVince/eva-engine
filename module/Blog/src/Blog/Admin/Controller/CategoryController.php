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
       
        $categoryModel = Api::_()->getModel('Blog\Model\Category');
        $categories = $categoryModel->setItemListParams($selectQuery)->getCategories();
        $paginator = $categoryModel->getPaginator();

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
        $itemModel = Api::_()->getModel('Blog\Model\Category');
        $categoryinfo = $itemModel->setItemParams($id)->getCategory();
        return array(
            'category' => $categoryinfo,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restGetCategoryRemove()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $itemModel = Api::_()->getModel('Blog\Model\Category');
        $categoryinfo = $itemModel->setItemParams($id)->getCategory();
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
        
        $subForms = array(
            'FileCategory' => array('File\Form\FileCategoryForm'),
        );
        $form->setSubforms($subForms)->init();
        $form->setData($categoryData)->enableFilters();
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Category');
            $postData = $form->fieldsMap($postData, true);
            $itemId = $itemModel->setSubItemMap($subForms)->setItem($postData)->createCategory();
            $this->flashMessenger()->addMessage('category-create-succeed');
            $this->redirect()->toUrl('/admin/blog/category/' . $itemId);
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
        $postData = $request->getPost();
        $form = new Form\CategoryEditForm();
        $subForms = array(
            'FileCategory' => array('File\Form\FileCategoryForm'),
        );

        $form->setSubforms($subForms)
             ->init()
             ->setData($postData)
             ->enableFilters();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Category');
            $postData = $form->fieldsMap($postData, true);
            $itemId = $itemModel->setSubItemMap($subForms)->setItem($postData)->saveCategory();
            $this->flashMessenger()->addMessage('category-edit-succeed');
            $this->redirect()->toUrl('/admin/blog/category/' . $itemId);
        } else {

            //p($form->getInputFilter()->getInvalidInput());
        }

        return array(
            'form' => $form,
            'category' => $postData,
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
            $itemModel = Api::_()->getModel('Blog\Model\Category');
            $itemId = $itemModel->setItem($categoryData)->deleteCategory();
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
