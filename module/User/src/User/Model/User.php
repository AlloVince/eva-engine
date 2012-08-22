<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModelService;

class User extends AbstractModelService
{
    protected $itemTableName = 'User\DbTable\Users';

    public function getUsers()
    {
    }
}
