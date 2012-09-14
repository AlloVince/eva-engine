<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class RoleUser extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\RolesUsers';
}
