<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class ResetController extends RestfulModuleController
{
    protected $renders = array(
        'restPutReset' => 'reset/index',
    );

    public function restIndexReset()
    {
        $this->layout('layout/adminblank');
    }

    public function restPutReset()
    {
        $this->layout('layout/adminblank');
        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/admin/';

        $item = $this->getRequest()->getPost();
        $form = new \User\Form\ResetForm();
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
