<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class FieldRole extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\FieldsRoles';

    protected $relationships = array(
    );

    protected $map = array(
        'create' => array(
        ),
    );
}
