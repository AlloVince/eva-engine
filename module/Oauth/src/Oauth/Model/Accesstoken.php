<?php

namespace Oauth\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel,
    Zend\Authentication\Result;

class Accesstoken extends AbstractModel
{
    protected $user;
    
    protected $loginResult;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getLoginResult()
    {
        return $this->loginResult;
    }

    public function bindToken(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $user = $this->getUser();
        if(!isset($user['id']) || !$user['id']){
            throw new \Exception(sprintf(
                'No user found when bind oauth token in %s',
                get_class($this)
            ));
        }

        $item = $this->getItem();
        
        $this->trigger('bind.pre');

        if($item->selfExist(array('*'))){
            $item->save();
        } else {
            $item->create();
        }

        $this->trigger('bind');
    
        $this->trigger('bind.post');

        return $item;
    }

    public function login()
    {
        $item = $this->getItem();
        
        $this->trigger('login.pre');

        $loginItem = clone $item;
        $loginItem->self(array('*'));
        if($loginItem->user_id){
            $item = $loginItem;
            $userModel = Api::_()->getModel('User\Model\Login');
            $this->loginResult = $userModel->loginById($item->user_id);
        }

        $this->trigger('login');

        $this->loginResult = new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $loginItem->user_id, array(
            Result::FAILURE_IDENTITY_NOT_FOUND => 'A record with the supplied identity could not be found.'
        )); 

        $this->trigger('login.post');

        return $this->loginResult; 
    }




    public function unbindToken()
    {
    
    }
}
