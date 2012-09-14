<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Role extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Roles';
}
