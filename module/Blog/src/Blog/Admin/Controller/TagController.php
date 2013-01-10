<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class TagController extends RestfulModuleController
{
    protected $addResources = array(
        'create',
        'remove'
    );

    protected $renders = array(
        'restGetTagCreate' => 'tag/get',    
        'restPostTag' => 'tag/get',    
        'restPutTag' => 'tag/get',    
        'restDeleteTag' => 'tag/remove',    
    ); 

    public function restIndexTag()
    {
        $query = $this->getRequest()->getQuery();

        $form = new Form\TagSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        }
       
        $itemModel = Api::_()->getModel('Blog\Model\Tag');
        $items = $itemModel->setItemList($query)->getTagList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetTagCreate()
    {
    }

    public function restGetTag()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Blog\Model\Tag');
        $item = $itemModel->getTag($id);
        return array(
            'item' => $item,
        );
    }

    public function restGetTagRemove()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Blog\Model\Tag');
        $item = $itemModel->getTag($id);
        return array(
            'item' => $item,
            'callback' => $this->params()->fromQuery('callback'),
        );
    }

    public function restPostTag()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\TagCreateForm();
        $form->useSubFormGroup();
        $form->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();

            $itemModel = Api::_()->getModel('Blog\Model\Tag');
            $itemId = $itemModel->setItem($postData)->createTag();
            
            $this->flashMessenger()->addMessage('tag-create-succeed');
            $this->redirect()->toUrl('/admin/blog/tag/' . $itemId);
        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restPutTag()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\TagEditForm();
        $form->useSubFormGroup();
        $form->bind($postData);

        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Tag');
            $itemModel->setItem($postData)->saveTag();
            
            $this->flashMessenger()->addMessage('tag-edit-succeed');
            $this->redirect()->toUrl('/admin/blog/tag/' . $postData['id']);
        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteTag()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\TagDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Tag');
            $itemId = $itemModel->setItem($postData)->removeTag();
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
