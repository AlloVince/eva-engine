<?php
namespace Epic\Controller;

use Eva\Mvc\Controller\ActionController;
use Eva\View\Model\ViewModel;
use Eva\Api;
use Core\Auth;
use User\Form;

class ResetController extends ActionController
{
    public function indexAction()
    {
        if(!$this->getRequest()->isPost()){
            return;
        }
        return $this->resetAction();
    }

    public function processAction()
    {
        if($this->getRequest()->isPost()){
            return $this->restPutResetProcess();
        }

        $itemModel = Api::_()->getModel('User\Model\Reset');
        if(!$itemModel->verifyRequestCode($this->params()->fromQuery('code'))){
            $this->flashMessenger()->addMessage('reset-password-code-verify-failed');
            return $this->redirect()->toUrl('/login/');
        }

        return array(
            'item' => $this->getRequest()->getQuery()
        );
    }

    public function restPutResetProcess()
    {
        $form = new Form\ResetPasswordForm();
        $form->bind($this->params()->fromPost());
        if($form->isValid()){

            $item = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\Reset');
            $itemModel->resetProcess($item['code'], $item['password']);
            return $this->redirect()->toUrl('/login/');
        
        } else {
        
        }

        return array(
            'form' => $form
        );
    }

    public function resetAction()
    {
        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/login/';

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
            ->setTemplatePath(Api::_()->getModulePath('Epic') . '/view/')
            ->setTemplate('mail/reset');
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
