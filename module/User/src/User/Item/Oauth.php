<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Oauth extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Oauths';

    protected $relationships = array(
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'ManyToOne',
        ),
    );
}
