<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Account extends AbstractModel
{
    protected $itemClass = 'User\Item\User';

    public function changePassword(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('changepassword.pre');

        $item->save();

        $this->trigger('changepassword');

        $this->trigger('changepassword.post');

        return $item->id;
    }

    public function changeEmail(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('changeemail.pre');

        $item->save();

        $this->trigger('changeemail');

        $this->trigger('changeemail.post');

        return $item->id;
    }
}
