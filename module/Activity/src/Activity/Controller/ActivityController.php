<?php
namespace Activity\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Activity\Form;
use Eva\Api;

class ActivityController extends RestfulModuleController
{
    protected $renders = array(
        'restPostActivity' => 'blank'
    );

    public function restPostActivity()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Activity\Model\Activity');
            $postId = $itemModel->setItem($postData)->createActivity();
            $this->redirect()->toUrl('/feed/');

        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

}
