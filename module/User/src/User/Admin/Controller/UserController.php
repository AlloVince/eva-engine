<?php
namespace User\Admin\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class UserController extends RestfulModuleController
{
    protected $renders = array(
        'restPutUser' => 'blog/get',    
        'restPostUser' => 'user/get',    
        'restDeleteUser' => 'remove/get',    
    );

    public function restIndexUser()
    {
        $request = $this->getRequest();

        $query = $request->getQuery();

        $form = Api::_()->getForm('User\Form\UserSearchForm');
        $selectQuery = $form->fieldsMap($query, true);

        $itemModel = Api::_()->getModelService('User\Model\User');
        $itemModel->getCache();
        //p($itemModel);
        //$items = $itemModel->getUsers();
        //$paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'posts' => $items,
            'query' => $query,
            //'paginator' => $paginator,
        );
    }

    public function restGetUser()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $itemModel = Api::_()->getModel('User\Model\User');
        $item = $itemModel->setItemParams($id)->getUser();
        return array(
            'user' => $item,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPostUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\UserForm();

        $subForms = array(
            'Profile' => array('User\Form\ProfileForm'),
            'Account' => array('User\Form\AccountForm'),
        );
        $form->setSubforms($subForms)->init();

        $form->setData($postData)->enableFilters();
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');
            //$itemData = $form->fieldsMap($postData, true);
            $itemId = $itemModel->setItem($postData)->createUser();
            //$this->flashMessenger()->addMessage('item-create-succeed');
            //$this->redirect()->toUrl('/admin/user/' . $itemId);

        } else {
            
            //p($form->getInputFilter()->getInvalidInput());
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\PostEditForm();
        $subForms = array(
            'Text' => array('User\Form\TextForm'),
            'CategoryPost' => array('User\Form\CategoryPostForm'),
            'FileConnect' => array('File\Form\FileConnectForm'),
        );
        
        $form->setSubforms($subForms)
             ->init()
             ->setData($postData)
             ->enableFilters();

        $flashMesseger = array();
        if ($form->isValid()) {
            $postData = $form->getData();
            $postModel = Api::_()->getModel('User\Model\Post');
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

    public function restDeleteUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\PostDeleteForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $postTable = Api::_()->getDbTable('User\DbTable\Posts');

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

