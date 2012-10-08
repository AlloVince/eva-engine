<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Token extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Tokens';

    protected $map = array(
        'create' => array(
            'getSessionId()',
            'getUserHash()',
            'getToken()',
            'getRefreshTime()',
            'getExpiredTime()',
        ),
        'save' => array(
            'getToken()',
            'getRefreshTime()',
            'getExpiredTime()',
        ),
    );

    private $tokenSalt = 'Eva_Login_Token_Salt';

    public function create()
    {
        $dataClass = $this->getDataClass();
        $data = $this->toArray(
            isset($this->map['create']) ? $this->map['create'] : array()
        );
        return $dataClass->create($data);
    }

    public function save()
    {
        //Because refresh token will update primary key, need to get primary array first
        $where = $this->getPrimaryArray();
        $dataClass = $this->getDataClass();
        $data = $this->toArray(
            isset($this->map['save']) ? $this->map['save'] : array()
        );
        $dataClass->where($where)->save($data);
        return true;
    }

    public function setTokenSalt($tokenSalt)
    {
        $this->tokenSalt = $tokenSalt;
        return $this;
    }

    public function getTokenSalt()
    {
        return $this->tokenSalt;
    }

    public function getSessionId()
    {
        $sessionManager = \Core\Session::getSessionManager();
        return $this->sessionId = $sessionId = $sessionManager->getId();
    }

    public function getUserHash()
    {
        $userid = $this->user_id;

        if(!$userid){
            throw new \Core\Exception\InvalidArgumentException(sprintf(
                'No user id found when generate user hash.'
            ));
        }

        $tokenSalt = $this->getTokenSalt();
        return $this->userHash = md5($tokenSalt . $userid);
    }

    public function getToken()
    {
        return $this->token = $token = md5(uniqid(rand(), true));
    }

    public function getRefreshTime()
    {
        return $this->refreshTime = \Eva\Date\Date::getNow();
    }

    public function getExpiredTime()
    {
        return $this->expiredTime = \Eva\Date\Date::getFuture(3600 * 24 * 60, $this->refreshTime, 'Y-m-d H:i:s');
    }
}
