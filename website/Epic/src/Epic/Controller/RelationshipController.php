<?php
namespace Epic\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;
use Core\Auth;

class RelationshipController extends ActionController
{
    protected function changeRelationship($funcName)
    {
        $item = $this->params()->fromPost();

        $form = new Form\FriendForm();
        $form->bind($item);

        if($form->isValid()){
            $item = $form->getData();
            $user = \Core\Auth::getLoginUser();
            $item['user_id'] = $user['id'];
            $itemModel = Api::_()->getModel('User\Model\Friend');
            $item = $itemModel->setItem($item)->$funcName();
        }
        $callback = $this->params()->fromPost('callback');
        return $this->redirect()->toUrl($callback);
    }

    public function requestAction()
    {
         $item = $this->params()->fromPost();

        $form = new Form\FriendForm();
        $form->bind($item);

        if($form->isValid()){
            $item = $form->getData();
            $user = \Core\Auth::getLoginUser();
            $item['user_id'] = $user['id'];
            $item['request_user_id'] = $user['id'];
            $itemModel = Api::_()->getModel('User\Model\Friend');
            $item = $itemModel->setItem($item)->requestFriend();
        }
        $callback = $this->params()->fromPost('callback');
        return $this->redirect()->toUrl($callback);
    }

    public function approveAction()
    {
        return $this->changeRelationship('approveFriend');
    }

    public function refuseAction()
    {
        return $this->changeRelationship('refuseFriend');
    }

    public function unfriendAction()
    {
        return $this->changeRelationship('unFriend');
    }

    public function blockAction()
    {
        return $this->changeRelationship('blockFriend');
    }

    public function unblockAction()
    {
        return $this->changeRelationship('unblockFriend');
    }
}
