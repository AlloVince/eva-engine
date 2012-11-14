<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Reset extends AbstractModel
{
    protected $itemClass = 'User\Item\User';

    public function resetRequest(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        $item->self(array('*'));

        $this->trigger('resetrequest.pre');

        $codeItem = $this->getItem('User\Item\Code');
        $codeItem->user_id = $item->id;
        $codeItem->codeType = 'resetPassword';
        $codeItem->setSalt($item->salt);
        $codeItem->create();

        $this->trigger('resetrequest');
    
        $this->trigger('resetrequest.post');

        return $codeItem;
    }

    public function verifyRequestCode($code)
    {
        if(!$code){
            return false;
        }

        $codeItem = $this->getItem('User\Item\Code');
        $codeItem->code = $code;
        $codeItem->self(array('*'));

        if(!$codeItem->code){
            return false;
        }

        if($codeItem->codeStatus != 'active'){
            return false;
        }

        if($codeItem->getCode() != $code){
            return false;
        }

        return true;
    }

    public function resetProcess($code, $newPassword)
    {
        $this->trigger('resetprocess.pre');

        if(!$this->verifyRequestCode($code)){
            throw new \Exception('Password reset code verify failed');
        }

        $codeItem = $this->getItem('User\Item\Code');
        $userId = $codeItem->user_id;

        $this->setItem(array(
            'id' => $userId,
        ));
        $item = $this->getItem();
        $item->self(array('*'));

        $salt = $item->salt;
        $oldPassword = $item->password;
        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setSalt($salt);
        $item->password = $bcrypt->create($newPassword);
        $item->oldPassword = $oldPassword;
        $item->lastPasswordChangeTime = \Eva\Date\Date::getNow();

        $this->trigger('resetprocess');

        $item->save();

        $codeItem->clear();
        $codeItem->getDataClass()->where(array(
            'code' => $code,
        ))->save(array(
            'codeStatus' => 'used',
            'used_by_id' => $userId,
            'usedTime' => \Eva\Date\Date::getNow()
        ));

        //One code used will expire all other active codes
        $codeItem->getDataClass()->where(array(
            'codeType' => 'resetPassword',
            'codeStatus' => 'active',
            'user_id' => $userId,
        ))->save(array(
            'codeStatus' => 'expired',
            'expiredTime' => \Eva\Date\Date::getNow()
        ));
    
        $this->trigger('resetprocess.post');
    }
}
