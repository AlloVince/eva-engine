<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Friend extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Friends';

    protected $relationships = array(
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
        ),
    );
}
