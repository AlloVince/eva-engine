<?php

namespace Contacts\Model;

use Eva\Api,
    Core\Mail,
    Eva\Mail\Message,
    Eva\Mvc\Model\AbstractModel;

class Invite extends AbstractModel
{
    protected $user;
    
    protected $regUrl;
    
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    
    public function getRegUrl()
    {
        $user = $this->user;
        $itemModel = Api::_()->getModel('User\Model\Invite');
        $code = $itemModel->setUser($user)->getUserInviteHash();
        
        if ($this->regUrl) {
            return $this->regUrl . "?code=" . $code;
        }    
    }

    public function setRegUrl($regUrl)
    {
        $this->regUrl = $regUrl;
        return $this;
    }

    public function sendInvite($params = array())
    {
        $emails       = $params['emails'];
        $template     = $params['template'];
        $templatePath = $params['templatePath'];
        
        if (!$emails || !$template || !$templatePath) {
            return array();
        }

        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $this->getUser();
        $this->user = $mine = $userModel->getUser($mine['id']);

        if (!$mine) {
            return false;
        }

        $subject = isset($params['subject']) ? $params['subject'] : $this->getSubject();

        $mail = new Mail();
        $message = $mail->getMessage();
        $message->addFrom($mine['email'], $mine['userName']);

        foreach ($emails as $email) {
            $message->addBcc($email);
        }
        
        $message->setSubject($subject)
            ->setData(array(
                'user' => $this->user,
                'url' => $this->getRegUrl(),
            ))
            ->setTemplatePath($templatePath)
            ->setTemplate($template);

        return $mail->send($message);
    }
}
