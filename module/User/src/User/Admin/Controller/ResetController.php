<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    User\Form;

class ResetController extends RestfulModuleController
{
    protected $addResources = array(
        'process',
    );

    protected $renders = array(
        'restPutReset' => 'reset/index',
    );

    public function restIndexReset()
    {
        $this->layout('layout/adminblank');
    }

    public function restGetResetProcess()
    {
        $this->layout('layout/adminblank');
        $itemModel = Api::_()->getModel('User\Model\Reset');
        if(!$itemModel->verifyRequestCode($this->params()->fromQuery('code'))){
            $this->flashMessenger()->addMessage('reset-password-code-verify-failed');
            return $this->redirect()->toUrl('/admin/');
        }

        return array(
            'item' => $this->getRequest()->getQuery()
        );
    }

    public function restPutResetProcess()
    {
        $this->layout('layout/adminblank');
        $form = new Form\ResetPasswordForm();
        $form->bind($this->params()->fromPost());
        if($form->isValid()){

            $item = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\Reset');
            $itemModel->resetProcess($item['code'], $item['password']);
            return $this->redirect()->toUrl('/admin/');
        
        } else {
        
        }

        return array(
            'form' => $form
        );
    }

    public function restPutReset()
    {
        $this->layout('layout/adminblank');
        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/admin/';

        $item = $this->getRequest()->getPost();
        $form = new Form\ResetForm();
        $form->bind($item);

        if ($form->isValid()) {

            $itemModel = Api::_()->getModel('User\Model\Reset');
            $itemModel->setItem($form->getData());
            $codeItem = $itemModel->resetRequest();
            $userItem = $itemModel->getItem();

            $mail = new \Core\Mail();
            $mail->getMessage()
            ->setSubject("Reset Password")
            ->setData(array(
                'user' => $userItem,
                'code' => $codeItem,
            ))
            ->setTo($userItem->email, $userItem->userName)
            ->setTemplatePath(EVA_MODULE_PATH . '/User/view/')
            ->setTemplate('_admin/mail/reset');
            $mail->send();
            
            return $this->redirect()->toUrl($callback);
        } else {
        }

        return array(
            'form' => $form,
            'item' => $form->getData(),
        );

    }
}
