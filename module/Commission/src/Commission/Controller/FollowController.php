<?php
namespace Commission\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Commission\Form;
use Eva\Api;

class FollowController extends RestfulModuleController
{
    protected $renders = array(
        'restPostFollow' => 'blank',
        'restDeleteFollow' => 'blank',
    );

    public function restIndexFollow()
    {
    
    }

    public function restPostFollow()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\FollowForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/feed/';

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Commission\Model\Follow');
            $postId = $itemModel->setItem($postData)->followUser();
            $this->redirect()->toUrl($callback);
        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restDeleteFollow()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\FollowForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/feed/';
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Commission\Model\Follow');
            $postId = $itemModel->setItem($postData)->unfollowUser();
            $this->redirect()->toUrl($callback);
        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

}
