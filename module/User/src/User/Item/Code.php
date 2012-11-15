<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Code extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Codes';

    private $salt;

    protected $map = array(
        'create' => array(
            'getCodeSalt()',
            'getCodeStatus()',
            'getCode()',
            'getCreateTime()',
            'getExpiredTime()',
        ),
        'save' => array(
        ),
    );

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function getCodeStatus()
    {
        return $this->codeStatus = 'active';
    }

    public function getCodeSalt()
    {
        if(!$this->codeSalt){
            return $this->codeSalt = \Eva\Stdlib\String\Hash::uniqueHash();
        }
        return $this->codeSalt;
    }

    public function getCode()
    {
        if($this->user_id && $this->codeType && $this->codeSalt){
            return $this->code = md5($this->user_id . '_' . $this->codeType . '_' . $this->codeSalt);
        }
        throw new \Exception(sprintf(
            'User code generation failed'
        ));
    }

    public function getCreateTime()
    {
        return $this->createTime = \Eva\Date\Date::getNow();
    }

    public function getExpiredTime()
    {
        //expired is 10 days
        return $this->expiredTime = \Eva\Date\Date::getFuture(3600 * 24 * 10, $this->createTime, 'Y-m-d H:i:s');
    }
}
