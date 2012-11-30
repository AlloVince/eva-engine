<?php
    
namespace Webservice\Adapter\User;

use Webservice\Adapter\AbstractUniform;
use Webservice\Exception;

abstract class AbstractUser extends AbstractUniform implements UserInterface
{

    protected $userId;

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUser()
    {
        return $this->getData('User');
    }

    public function getProfile()
    {
        return $this->getData('Profile');
    }

}
