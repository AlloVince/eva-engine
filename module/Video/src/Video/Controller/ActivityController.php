<?php
namespace Video\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Video\Form;
use Eva\Api;

class VideoController extends RestfulModuleController
{
    protected $renders = array(
        'restPostVideo' => 'blank'
    );

    public function restPostVideo()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Video\Model\Video');
            $postId = $itemModel->setItem($postData)->createVideo();
            $this->redirect()->toUrl('/feed/');

        } else {
            
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

}
