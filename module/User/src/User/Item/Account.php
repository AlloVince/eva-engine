<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Account extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Accounts';

    protected $relationships = array(
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'user_id',
            'referencedColumnName' => 'id',
        ),
    );
}
