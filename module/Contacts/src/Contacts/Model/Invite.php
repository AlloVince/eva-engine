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
        if ($this->regUrl) {
            return $this->regUrl . "?invitees=" . $this->user['userName'];
        }    
    }

    public function setRegUrl($regUrl)
    {
        $this->regUrl = $regUrl;
        return $this;
    }
    
    public function getBody()
    {
        return "Your friend " . $this->user['userName'] . "invite you join :\n" . $this->getRegUrl();
    }
    
    public function getSubject()
    {
        return "Invite";
    }

    public function sendInvite($params = array())
    {
        $emails = $params['emails'];
        
        if (!$emails) {
            return array();
        }

        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $this->getUser();
        $this->user = $mine = $userModel->getUser($mine['id']);

        if (!$mine) {
            return false;
        }

        $body = isset($params['body']) ? $params['body'] : $this->getBody();
        $subject = isset($params['subject']) ? $params['subject'] : $this->getSubject();

        $message = new Message();
        $message->addFrom($mine['email'], $mine['userName']);

        foreach ($emails as $email) {
            $message->addBcc($email);
        }
        
        $message->setSubject($subject);
        $message->setBody($body);

        $mail = new Mail();

        return $mail->send($message);
    }
}
