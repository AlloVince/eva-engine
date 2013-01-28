<?php
namespace User\Jobs;

use Eva\Api;
use Eva\Job\IndependentJobInterface;

class ResetPassword implements IndependentJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;
        $itemModel = Api::_()->getModel('User\Model\Reset');
        $itemModel->setItem($args);
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
    }
}
