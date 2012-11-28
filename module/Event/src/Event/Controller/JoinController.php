<?php
namespace Event\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Event\Form;
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
        $form = new Form\EventUserForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/events/';

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Event\Model\EventUser');
            $postData['requestStatus'] = 'active';
            $postData['role'] = 'member';
            $itemModel->setItem($postData)->joinEvent();
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
        $form = new Form\EventUserForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/events/';
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Event\Model\EventUser');
            $postId = $itemModel->setItem($postData)->unjoinEvent();
            $this->redirect()->toUrl($callback);
        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

}
