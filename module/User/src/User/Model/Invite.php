<?php

namespace User\Model;

use Eva\Api;
use Eva\Mvc\Model\AbstractModel;
use Zend\Math\BigInteger\BigInteger;

class Invite extends AbstractModel
{
    const IS_EMPTY          = 'isEmpty';
    const NOT_EXIST         = 'notExist';
    const EXPIRED           = 'expired';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::IS_EMPTY => "Invite code is empty",
        self::NOT_EXIST => "Invite code not exist",
        self::EXPIRED => "Invite code already expired",
    );

    protected $itemClass = 'User\Item\Code';
    protected $user;
    protected $code;
    protected $message;

    public function getMessage()
    {
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getUserInviteHash()
    {
        $user = $this->getUser();
        if(!isset($user['id']) && !$user['id']){
            throw new \Exception(sprintf('No user set when get user hash in %s', get_class($this)));
        }

        $bigInt = BigInteger::factory('bcmath');
        $userId = $bigInt->add($user['id'], '100000000');
        return $this->code = \Eva\Stdlib\String\Hash::shortHash($userId);
    }

    public function getUserInviteCodeList()
    {
    
    }

    public function getUserIdFromHash()
    {
        $code = $this->getCode();

        $bigInt = BigInteger::factory('bcmath');
        $userId = \Eva\Stdlib\String\Hash::shortHash($code, true);
        $userId = $bigInt->sub($userId, '100000000');
        return $userId;
    }

    public function getUserIdFromCode()
    {
    
    }

    public function getUserId()
    {
        return $this->getUserIdFromHash();
    }

    public function isValid()
    {
        $code = $this->getCode();
        return true;

    }


    public function isHashValid()
    {
    
    }

    public function isCodeValid()
    {
    
    }

    public function updateInviteUser()
    {
        $request = $this->getServiceLocator()->get('request');
        $userData = $request->getPost();
        if(!isset($userData->code)){
            return;
        }

        $userItem = $this->getItem('User\Item\User');
        $code = $userData->code;
        $this->setCode($code);

        if(!$code || !$this->isValid()){
            return;
        }
        $inviteUserId = $this->getUserId();
        $userItem->inviteUserId = $inviteUserId;
        $userItem->save();
    }

    public function createInviteCode()
    {
        $this->trigger('create_invite_code.pre');
        $userItem = $this->getUser();
        $codeItem = $this->getItem();
        $codeItem->user_id = $userItem->id;
        $codeItem->codeType = 'invite';
        
        $this->trigger('create_invite_code');
        $codeItem->create();

        $this->trigger('create_invite_code.post');
    }


}
