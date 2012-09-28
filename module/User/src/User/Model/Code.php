<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Code extends AbstractModel
{
    public function createActiveCode()
    {
        $this->trigger('create_active_code.pre');
        $userItem = $this->getItem('User\Item\User');
        $codeItem = $this->getItem();
        $codeItem->user_id = $userItem->id;
        $codeItem->codeType = 'verifyEmail';
        $codeItem->setSalt($userItem->salt);
        $codeItem->create();
        $this->trigger('create_active_code');
    }
}
