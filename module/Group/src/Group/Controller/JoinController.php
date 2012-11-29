<?php
namespace Group\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Group\Form;
use Eva\Api;

class JoinController extends RestfulModuleController
{
    protected $renders = array(
        'restPostJoin' => 'blank',
        'restDeleteJoin' => 'blank',
    );

    public function restIndexJoin()
    {
    
    }

    public function restPostJoin()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\GroupUserForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/groups/';

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Group\Model\GroupUser');
            $postData['requestStatus'] = 'active';
            $postData['role'] = 'member';
            $itemModel->setItem($postData)->joinGroup();
            $this->redirect()->toUrl($callback);
        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restDeleteJoin()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\GroupUserForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/groups/';
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Group\Model\GroupUser');
            $postId = $itemModel->setItem($postData)->unjoinGroup();
            $this->redirect()->toUrl($callback);
        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

}
