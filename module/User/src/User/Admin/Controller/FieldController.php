<?php
namespace User\Admin\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class FieldController extends RestfulModuleController
{
    protected $addResources = array(
        'create',
        'remove',
    );

    protected $renders = array(
        'restGetFieldCreate' => 'field/get',
        'restPutField' => 'field/get',
        'restPostField' => 'field/get',
        'restDeleteField' => 'remove/get',
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
        return array();
    }

    public function restGetFieldRemove()
    {
        return array();
    }

    public function restGetField()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $itemModel = Api::_()->getModelService('User\Model\Field');
        $item = $itemModel->getField($id);

        $item = $item->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Profile' => array(
                    'site',
                    'birthday',
                    'phoneMobile',
                ),
                'Account' => array('*'),
                'MyFriends' => array(
                    'self' => array(
                        'fieldName',
                    ),
                    'join' => array(
                        'Profile' => array()
                    )
                ),
                'Oauth' => array(
                    //'appExt'
                ),
            ),
        ));

        return array(
            'item' => $item,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPostField()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\FieldForm();
        $subForms = array();
        /*
        $subForms = array(
            'Profile' => array('Field\Form\ProfileForm'),
            'Account' => array('Field\Form\AccountForm'),
        );
        */
        $form->setSubforms($subForms)
             ->init()
             ->setData($postData)
             ->enableFilters();

        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\Field');
            $itemId = $itemModel->setItem($postData)->createField();
            $this->flashMessenger()->addMessage('item-create-succeed');
            $this->redirect()->toUrl('/admin/user/field/' . $itemId);

        } else {
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
        $subForms = array();
        /*
        $subForms = array(
            'Profile' => array('Field\Form\ProfileForm'),
            'Account' => array('Field\Form\AccountForm'),
        );
        */
        $form->setSubforms($subForms)
             ->init()
             ->setData($postData)
             ->enableFilters();

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
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('Field\Model\Field');
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

