<?php
namespace Activity\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Activity\Form;
use Eva\Api;

class FollowController extends RestfulModuleController
{
    protected $renders = array(
        'restPostFollow' => 'blank'
    );

    public function restPostFollow()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\FollowForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback', '/feed/');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Activity\Model\Follow');
            $postId = $itemModel->setItem($postData)->followUser();
            $this->redirect()->toUrl($callback);
        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

}
