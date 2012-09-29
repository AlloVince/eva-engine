<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

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
}
