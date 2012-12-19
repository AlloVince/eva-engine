<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class Count extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\Counts';

    protected $map = array(
        'create' => array(
        ),
    );
}
