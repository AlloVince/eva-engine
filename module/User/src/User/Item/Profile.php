<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Profile extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Profiles';

    protected $relationships = array(
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
        ),
    );
}
