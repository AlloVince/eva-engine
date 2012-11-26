<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Code extends AbstractModel
{
    /**
    * Code verify Failure
    */
    const FAILURE                        =  0;

    /**
    * Failure due to identity not being found.
    */
    const FAILURE_CODE_NOT_FOUND     = -1;

    /**
    * Failure due to code expired
    */
    const FAILURE_CODE_EXPIRED     = -2;

    /**
    * Failure due to code used
    */
    const FAILURE_CODE_USED     = -3;

    /**
    * Failure due to invalid credential being supplied.
    */
    const FAILURE_CODE_INVALID     = -4;


    /**
    * Authentication success.
    */
    const SUCCESS                        =  1;

    /**
    * Authentication result code
    *
    * @var int
    */
    protected $resultCode;


    /**
    * An array of string reasons why the authentication attempt was unsuccessful
    *
    * If authentication was successful, this should be an empty array.
    *
    * @var array
    */
    protected $messages;

    /**
    * getCode() - Get the result code for this authentication attempt
    *
    * @return int
    */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    public function setResultCode($code)
    {
        $this->resultCode = $code;
        return $this;
    }

    /**
    * Returns an array of string reasons why the authentication attempt was unsuccessful
    *
    * If authentication was successful, this method returns an empty array.
    *
    * @return array
    */
    public function getMessages()
    {
        return $this->messages;
    }


    public function createActiveCode()
    {
        $this->trigger('activecode.create.pre');
        $userItem = $this->getItem('User\Item\User');
        $codeItem = $this->getItem();
        $codeItem->user_id = $userItem->id;
        $codeItem->codeType = 'activeAccount';
        $codeItem->create();
        $this->trigger('activecode.create');
    }

    /**
     * Returns whether the result represents a successful authentication attempt
     *
     * @return boolean
     */
    public function isValid()
    {
        $codeItem = $this->getItem();
        if(!$codeItem->code){
            $this->resultCode = self::FAILURE_CODE_NOT_FOUND;
            $this->messages = 'Code not found';
            return false;
        }

        $codeItem->self(array('*'));
        if(!$codeItem){
            $this->resultCode = self::FAILURE_CODE_NOT_FOUND;
            $this->messages = 'Code not found';
            return false;
        }

        if($codeItem->codeStatus != 'active'){
            if($codeItem->codeStatus == 'expired'){
                $this->resultCode = self::FAILURE_CODE_EXPIRED;
                $this->messages = 'Code already expired';
            } elseif($codeItem->codeStatus == 'used'){
                $this->resultCode = self::FAILURE_CODE_USED;
                $this->messages = 'Code has been used';
            }
            return false;
        }

        $this->resultCode = self::SUCCESS;
        $this->messages = 'Code validated';
        return true;
    }

    public function activeAccount()
    {
        $codeItem = $this->getItem();

        $userId = $codeItem->user_id;
        $userItem = $this->getItem('User\Item\User');


        $codeItem->codeStatus = 'used';
        $codeItem->used_by_id = $userId;
        $codeItem->usedTime = \Eva\Date\Date::getNow();
        $codeItem->save();

        $userItem->clear();
        $userItem->getDataClass()->where(array(
            'id' => $userId,
        ))->save(array(
            'status' => 'active',
        ));

        //One code used will expire all other active codes
        $codeItem->getDataClass()->where(array(
            'codeType' => 'activeAccount',
            'codeStatus' => 'active',
            'user_id' => $userId,
        ))->save(array(
            'codeStatus' => 'expired',
            'expiredTime' => \Eva\Date\Date::getNow()
        ));

    }
}
