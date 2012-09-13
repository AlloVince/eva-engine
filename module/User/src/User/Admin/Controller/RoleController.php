<?php
namespace User\Admin\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class RoleController extends RestfulModuleController
{
    protected $addResources = array(
        'create',
        'remove',
    );

    protected $renders = array(
        'restGetRoleCreate' => 'role/get',
        'restPutRole' => 'role/get',
        'restPostRole' => 'role/get',
        'restDeleteRole' => 'role/remove',
    );

    public function restIndexRole()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();

        $itemModel = Api::_()->getModelService('User\Model\Role');

        $selectQuery = array(
            'page' => $request->getQuery('page', 1)
        );
        $items = $itemModel->setItemList($selectQuery)->getRoleList();
        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),
        ));
        $paginator = $itemModel->getPaginator();

        return array(
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetRoleCreate()
    {

    }

    public function restGetRoleRemove()
    {
        $id = (int)$this->params('id');
        
        $itemModel = Api::_()->getModelService('User\Model\Role');
        $item = $itemModel->getRole($id);
        return array(
            'callback' => $this->getRequest()->getQuery()->get('callback'),
            'item' => $item,
        );
    }

    public function restGetRole()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $itemModel = Api::_()->getModelService('User\Model\Role');
        $item = $itemModel->getRole($id);

        $item = $item->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
            ),
        ));
        return array(
            'item' => $item,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPostRole()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        
        $form = new Form\RoleForm();
        $form->useSubFormGroup()
        ->bind($postData);

        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\Role');
            $itemId = $itemModel->setItem($postData)->createRole();
            $this->flashMessenger()->addMessage('item-create-succeed');
            $this->redirect()->toUrl('/admin/user/role/' . $itemId);

        } else {
            //p($form->getMessages());
            //p($form->getData());
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutRole()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        
        $form = new Form\RoleEditForm();
        $form->useSubFormGroup()
        ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\Role');

            $itemId = $itemModel->setItem($postData)->saveRole();

            $this->flashMessenger()->addMessage('item-edit-succeed');
            $this->redirect()->toUrl('/admin/user/role/' . $postData['id']);
        } else {
            //$this->flashMessenger()->addMessage('');
            //$flashMesseger = array('post-edit-failed');
        }

        return array(
            'form' => $form,
            'role' => $postData,
            //'flashMessenger' => $flashMesseger
        );
    }

    public function restDeleteRole()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');
        
        $form = new Form\RoleDeleteForm();
        $form->bind($postData);

        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\Role');
            $itemModel->setItem(array(
                'id' => $postData['id']
            ))->removeRole();

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

