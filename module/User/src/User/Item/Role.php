<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Role extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Roles';

    protected $relationships = array(
    );

    protected $map = array(
        'create' => array(
        ),
    );

}
