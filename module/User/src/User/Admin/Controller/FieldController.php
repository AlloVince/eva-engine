<?php
namespace User\Admin\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class FieldController extends RestfulModuleController
{
    protected $addResources = array(
        'userfield',
        'create',
        'remove',
    );

    protected $renders = array(
        'restGetFieldUserfield' => 'user/field',
        'restPutFieldUserfield' => 'user/field',
        'restGetFieldCreate' => 'field/get',
        'restPutField' => 'field/get',
        'restPostField' => 'field/get',
        'restDeleteField' => 'field/remove',
    );

    public function restIndexField()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();

        $itemModel = Api::_()->getModelService('User\Model\Field');

        $selectQuery = array(
            'page' => $request->getQuery('page', 1)
        );
        $items = $itemModel->setItemList($selectQuery)->getFieldList();
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

    public function restGetFieldCreate()
    {

    }

    public function restGetFieldRemove()
    {
        $id = (int)$this->params('id');
        
        $itemModel = Api::_()->getModelService('User\Model\Field');
        $item = $itemModel->getField($id);
        return array(
            'callback' => $this->getRequest()->getQuery()->get('callback'),
            'item' => $item,
        );
    }

    public function restGetField()
    {
        $id = (int)$this->params('id');
        $itemModel = Api::_()->getModelService('User\Model\Field');
        $item = $itemModel->getField($id);

        $item = $item->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Fieldoption' => array(
                    '*',
                ),
                'Roles' => array(
                    '*',
                )
            ),
        ));
        return array(
            'item' => $item,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restGetFieldUserfield()
    {
        $id = (int)$this->params('id');
        $itemModel = Api::_()->getModelService('User\Model\User');
        $item = $itemModel->getUser($id);

        $item = $item->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Roles' => array(
                    'self' => array(
                        '*'
                    ),
                ),
                'UserRoleFields' => array(),
            ),
        ));
        return array(
            'item' => $item,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPutFieldUserfield()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $roleId = $postData['role_id'];
        $form = new Form\UserConnectForm();
        $form->addSubForm('UserRoleFields', new Form\UserRoleFieldForm(null, $roleId))
        ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');
            $itemId = $itemModel->setItem($postData)->saveUser();
            $this->flashMessenger()->addMessage('item-create-succeed');
            $this->redirect()->toUrl('/admin/user/field/userfield/' . $itemId);

        } else {
            $id = $postData['id'];
            $itemModel = Api::_()->getModelService('User\Model\User');
            $item = $itemModel->getUser($id);
            $item = $item->toArray(array(
                'self' => array(
                    '*',
                ),
                'join' => array(
                    'Roles' => array(
                        'self' => array(
                            '*'
                        ),
                    )
                ),
            ));
        }


        return array(
            'item' => $postData,
        );
    }

    public function restPostField()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new Form\FieldForm();
        $form->useSubFormGroup()
        ->bind($postData);

        if ($form->isValid()) {

            $postData = $form->getData();

            $itemModel = Api::_()->getModelService('User\Model\Field');
            $itemId = $itemModel->setItem($postData)->createField();
            $this->flashMessenger()->addMessage('item-create-succeed');
            $this->redirect()->toUrl('/admin/user/field/' . $itemId);

        } else {
            //p($form->getMessages());
            //p($form->getData());
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutField()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new Form\FieldEditForm();
        $form->useSubFormGroup()
        ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\Field');

            $itemId = $itemModel->setItem($postData)->saveField();

            $this->flashMessenger()->addMessage('item-edit-succeed');
            $this->redirect()->toUrl('/admin/user/field/' . $postData['id']);
        } else {
            //$this->flashMessenger()->addMessage('');
            //$flashMesseger = array('post-edit-failed');
        }

        return array(
            'form' => $form,
            'field' => $postData,
            //'flashMessenger' => $flashMesseger
        );
    }

    public function restDeleteField()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\FieldDeleteForm();
        $form->bind($postData);

        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\Field');
            $itemModel->setItem(array(
                'id' => $postData['id']
            ))->removeField();

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

