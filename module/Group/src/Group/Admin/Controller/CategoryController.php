<?php
namespace Group\Admin\Controller;

use Group\Form,
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
        $query = $this->getRequest()->getQuery();

        $form = new Form\CategorySearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        }
       
        $itemModel = Api::_()->getModel('Group\Model\Category');
        $items = $itemModel->setItemList($query)->getCategoryList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetCategoryCreate()
    {
    }

    public function restGetCategory()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Group\Model\Category');
        $item = $itemModel->getCategory($id);
        return array(
            'item' => $item,
        );
    }

    public function restGetCategoryRemove()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Group\Model\Category');
        $item = $itemModel->getCategory($id);
        return array(
            'item' => $item,
            'callback' => $this->params()->fromQuery('callback'),
        );
    }

    public function restPostCategory()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\CategoryCreateForm();
        $form->useSubFormGroup();
        $form->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();

            $itemModel = Api::_()->getModel('Group\Model\Category');
            $itemId = $itemModel->setItem($postData)->createCategory();
            
            $this->flashMessenger()->addMessage('category-create-succeed');
            $this->redirect()->toUrl('/admin/group/category/' . $itemId);
        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restPutCategory()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\CategoryEditForm();
        $form->useSubFormGroup();
        $form->bind($postData);

        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Group\Model\Category');
            $itemModel->setItem($postData)->saveCategory();
            
            $this->flashMessenger()->addMessage('category-edit-succeed');
            $this->redirect()->toUrl('/admin/group/category/' . $postData['id']);
        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteCategory()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\CategoryDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Group\Model\Category');
            $itemId = $itemModel->setItem($postData)->removeCategory();
            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'item' => $postData,
            );
        }
    }
}
