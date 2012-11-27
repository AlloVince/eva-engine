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

        //Remove all expired tokens
        $item->getDataClass()->where(array(
            'adapterKey' => $item->adapterKey,
            'version' => $item->version,
            'user_id' => $user['id'],
        ))->remove();
        $item->create();

        $this->trigger('bind');

        $this->trigger('bind.post');

        return $item;
    }

    public function login()
    {
        $item = $this->getItem();
        
        $this->trigger('login.pre');

        $loginItem = clone $item;

        $userId = '';

        //Search for remoteUserId first
        if($loginItem->remoteUserId){
            $token = $loginItem->getDataClass()->where(array(
                'adapterKey' => $loginItem->adapterKey,
                'version' => $loginItem->version,
                'remoteUserId' => $loginItem->remoteUserId,
            ))->find('one');
            if($token){
                $loginItem->setDataSource((array) $token);
                $userId = $token['user_id'];
            }
        } else {

            //Search by token
            $loginItem->self(array('*'));
            if($loginItem->user_id){
                $userId = $loginItem->user_id;
            }
        }

        if($userId){
            $userModel = Api::_()->getModel('User\Model\Login');
            $this->loginResult = $userModel->loginById($userId);

            //Update token when login
            $item->getDataClass()->where(array(
                'adapterKey' => $item->adapterKey,
                'version' => $item->version,
                'user_id' => $userId,
            ))->save(array(
                'token' =>  $item->token,
                'tokenSecret' => $item->tokenSecret,
                'tokenStatus' => 'active',
                'refreshToken' => $item->refreshToken,
                'expireTime' => $item->expireTime,
            ));
        } else {
            $this->loginResult = new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $loginItem->user_id, array(
                Result::FAILURE_IDENTITY_NOT_FOUND => 'A record with the supplied identity could not be found.'
            )); 
        }

        $this->trigger('login');

        $this->trigger('login.post');

        return $this->loginResult; 
    }


    public function refreshToken()
    {
    
    }

    public function unbindToken()
    {
    
    }
}
