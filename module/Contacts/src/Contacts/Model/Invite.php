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
        return $this->regUrl;
    }

    public function setRegUrl($regUrl)
    {
        $this->regUrl = $regUrl;
        return $this;
    }

    public function sendInvite($emails)
    {
        if (!$emails) {
            return array();
        }

        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $this->getUser();
        $mine = $userModel->getUser($mine['id']);

        if (!$mine) {
            return false;
        }
        
        $mail = new mail();

    }
}
