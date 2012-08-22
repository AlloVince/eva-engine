<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class User extends AbstractModel
{
    protected $itemTableName = 'User\DbTable\Users';

    public function getUsers()
    {
    }
}
