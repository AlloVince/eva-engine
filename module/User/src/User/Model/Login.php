<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel,
    Core\Auth;

class Login extends AbstractModel
{
    protected $itemClass = 'User\Item\User';

    public function login(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('login.pre');

        $this->trigger('login.post');

        return $itemId;
    }

    public function loginByPassword($loginIdentity, $password)
    {
        $auth = new Auth('DbTable', 'Session');

        $user = $this->getItem()->getDataClass()->columns(array('id', 'salt'))->where(array(
            'userName' => $loginIdentity
        ))->find('one');

        if(!$user){
            return;
        }

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setSalt($user['salt']);
        $password = $bcrypt->create($password);

        $authResult = $auth->getAuthService(array(
            'tableName' => 'user_users',
            'identityColumn' => 'userName',
            'credentialColumn' => 'password',
        ))->getAdapter()->setIdentity(
            $loginIdentity
        )->setCredential(
            $password
        )->authenticate();

        if($authResult->isValid()){
            $auth->getAuthStorage()->write($authResult->getIdentity());
        }    
        return $authResult;
    }
}
