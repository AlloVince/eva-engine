<?php
namespace User\Admin\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class UserController extends RestfulModuleController
{
    protected $renders = array(
        'restPutUser' => 'user/get',    
        'restPostUser' => 'user/get',    
        'restDeleteUser' => 'remove/get',    
    );

    public function restIndexUser()
    {
        $request = $this->getRequest();

        $query = $request->getQuery();

        $form = new Form\UserSearchForm();
        $form->bind($query)->isValid();
        $selectQuery = $form->getData();

        $itemModel = Api::_()->getModelService('User\Model\User');
        if(!$selectQuery){
            $selectQuery = array(
                'page' => 1
            );
        }
        $items = $itemModel->setItemList($selectQuery)->getUserList();
        //p($items[0]->join('Profile')->self(array('*'))->site);
        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Profile' => array(
                    'site',
                    'birthday',
                    'phoneMobile',
                ),
            ),
        ));
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'users' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetUser()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModelService('User\Model\User');
        $item = $itemModel->getUser($id);

        $item = $item->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Profile' => array(
                    '*'
                ),
                'Roles' => array(
                    '*'
                ),
                'Account' => array('*'),
                'CommonField' => array('*'),
            ),
        ));
        return array(
            'item' => $item,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPostUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\UserForm();
        $form->useSubFormGroup()->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');
            $itemId = $itemModel->setItem($postData)->createUser();
            $this->flashMessenger()->addMessage('item-create-succeed');
            $this->redirect()->toUrl('/admin/user/' . $itemId);

        } else {
            p($form->getMessages());
        }

        return array(
            'form' => $form,
        );
    }

    public function restPutUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new Form\UserEditForm();
        $form->useSubFormGroup()->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');

            $itemId = $itemModel->setItem($postData)->saveUser();

            $this->flashMessenger()->addMessage('item-edit-succeed');
            $this->redirect()->toUrl('/admin/user/' . $postData['id']);
        } else {
            //$this->flashMessenger()->addMessage('');
            //$flashMesseger = array('post-edit-failed');
        }

        return array(
            'form' => $form,
        );
    }

    public function restDeleteUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\UserDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');
            $itemModel->setItem(array(
                'id' => $postData['id']
            ))->removeUser();

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

