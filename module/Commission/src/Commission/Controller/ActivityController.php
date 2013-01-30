<?php
namespace Commission\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Commission\Form;
use Eva\Api;

class CommissionController extends RestfulModuleController
{
    protected $renders = array(
        'restPostCommission' => 'blank'
    );

    public function restPostCommission()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback', '/feed/');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Commission\Model\Commission');
            $postId = $itemModel->setItem($postData)->createCommission();
            $this->redirect()->toUrl($callback);

        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

}
