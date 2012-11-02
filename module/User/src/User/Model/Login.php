<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel,
    Core\Auth,
    Zend\Authentication\Result;

class Login extends AbstractModel
{
    protected $itemClass = 'User\Item\User';

    protected $loginResult;

    protected $tokenString;

    protected $userId;

    public function getTokenString()
    {
        return $this->tokenString;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getLoginResult()
    {
        return $this->loginResult;
    }

    public function login(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $this->trigger('login.pre');

        $item = $this->getItem();
        if($item->inputPassword){
            $loginResult = $this->loginByPassword($item->loginName, $item->inputPassword);
        }


        $this->trigger('login.post');
        return true;
    }

    public function loginRefresh()
    {
        $userId = $this->getUserId();
        if(!$userId){
            throw new \Core\Exception\InvalidArgumentException(sprintf(
                'No user id found when refresh login'
            ));
        }

        $user = $this->getItem()->getDataClass()->where(array(
            'id' => $userId
        ))->save(array(
            'onlineStatus' => 'online',
            'lastLoginTime' => \Eva\Date\Date::getNow(),
            'lastLoginIp' => $_SERVER["REMOTE_ADDR"],
        ));
    
    }

    public function loginById($userId)
    {
        if($userId){
            $this->setUserId($userId);
        }

        $user = $this->getItem()->getDataClass()->columns(array('id', 'userName', 'status'))->where(array(
            'id' => $userId
        ))->find('one');

        if(!$user){
            return $this->loginResult = new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $userId, array(
                Result::FAILURE_IDENTITY_NOT_FOUND => 'A record with the supplied identity could not be found.'
            )); 
        }

        $user = $this->loadPermissions($user);
        Auth::factory()->saveLoginUser($user);
        $this->loginRefresh();
        return $this->loginResult = new Result(Result::SUCCESS, $userId, array(
            Result::SUCCESS => 'Authentication successful.'
        )); 
    }

    public function loginByToken($tokenString)
    {
        if($tokenString) {
            list($sessionId, $token, $userHash) = explode('|', $tokenString);
        }

        if(!$tokenString || !$sessionId || !$token || !$userHash){
            return $this->loginResult = new Result(Result::FAILURE, $tokenString, array(
                Result::FAILURE => 'Auto login arguments missing'
            )); 
        }

        $token = $this->getItem('User\Item\Token')->getDataClass()->columns(array('user_id', 'expiredTime'))->where(array(
            'sessionId' => $sessionId,
            'token' => $token,
            'userHash' => $userHash,
        ))->find('one');

        if(!$token || !$token['user_id']){
            return $this->loginResult = new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $tokenString, array(
                Result::FAILURE_IDENTITY_NOT_FOUND => 'A record with the supplied identity could not be found.'
            )); 
        }

        $expiredTime = new \DateTime($token['expiredTime']);
        $now = new \DateTime();
        if($expiredTime <= $now){
            return $this->loginResult = new Result(Result::FAILURE_UNCATEGORIZED, $tokenString, array(
                Result::FAILURE_UNCATEGORIZED => 'Login token expired.'
            )); 
        }

        $loginResult = $this->loginById($token['user_id']);
        if(!$loginResult->isValid()){
            $this->clearToken($tokenString);
        }
        return $loginResult;
    }

    public function loginByPassword($loginIdentity, $password)
    {
        $identityType = 'userName';
        if(is_numeric($loginIdentity)){
            $identityType = 'mobile';
        } else {
            $validator = new \Zend\Validator\EmailAddress();
            if ($validator->isValid($loginIdentity)) {
                $identityType = 'email';
            }
        }

        switch($identityType){

            case 'email':
            $dbWhere = array(
                'email' => $loginIdentity,
            );
            $identityColumn = 'email';
            break;

            case 'mobile':
            $dbWhere = array(
                'mobile' => $loginIdentity,
            );
            $identityColumn = 'mobile';
            break;

            default:
            $dbWhere = array(
                'userName' => $loginIdentity,
            );
            $identityColumn = 'userName';
        }

        $auth = Auth::factory();
        $user = $this->getItem()->getDataClass()->columns(array('id', 'salt', 'userName'))->where($dbWhere)->find('one');

        if(!$user || !$user['id']){
            return $this->loginResult = new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $loginIdentity, array(
                Result::FAILURE_IDENTITY_NOT_FOUND => 'A record with the supplied identity could not be found.'
            )); 
        }

        if(!$user['salt']){
            throw new \Exception(sprintf(
                'User authention salt not found'
            ));
        }

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setSalt($user['salt']);
        $password = $bcrypt->create($password);

        $this->loginResult = $loginResult = $auth->getAuthService(array(
            'tableName' => 'user_users',
            'identityColumn' => $identityColumn,
            'credentialColumn' => 'password',
        ))->getAdapter()->setIdentity(
            $loginIdentity
        )->setCredential(
            $password
        )->authenticate();

        if($loginResult->isValid()){
            return $this->loginById($user['id']);
        }    
        return $loginResult;
    }


    public function loadPermissions($user)
    {
        return $user;
    }


    public function createToken()
    {
        $loginResult = $this->getLoginResult();
        if(!$loginResult->isValid()){
            return false;
        }

        $tokenItem = $this->getItem('User\Item\Token');
        $tokenItem->user_id = $this->getUserId();
        $tokenItem->create();

        $tokenString = $tokenItem->sessionId . '|' 
            . $tokenItem->token . '|'
            . $tokenItem->userHash;

        return $tokenString;
    }

    public function refreshToken($tokenString)
    {
        if($tokenString) {
            list($sessionId, $token, $userHash) = explode('|', $tokenString);
        }

        if(!$tokenString || !$sessionId || !$token || !$userHash){
            throw new \Core\Exception\InvalidArgumentException(sprintf(
                'Login token missing arguments'
            ));
        }

        $tokenItem = $this->getItem('User\Item\Token');
        $tokenItem->sessionId = $sessionId;
        $tokenItem->token = $token;
        $tokenItem->userHash = $userHash;
        $tokenItem->save();

        $tokenString = $tokenItem->sessionId . '|' 
            . $tokenItem->token . '|'
            . $tokenItem->userHash;

        return $tokenString;
    }

    public function clearToken($tokenString)
    {
        if($tokenString) {
            list($sessionId, $token, $userHash) = explode('|', $tokenString);
        }

        if(!$tokenString || !$sessionId || !$token || !$userHash){
            throw new \Core\Exception\InvalidArgumentException(sprintf(
                'Login token missing arguments'
            ));
        }

        $tokenItem = $this->getItem('User\Item\Token');
        $tokenItem->sessionId = $sessionId;
        $tokenItem->token = $token;
        $tokenItem->userHash = $userHash;
        $tokenItem->remove();
    }

}
