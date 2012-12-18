<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class Count extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\Counts';

    protected $map = array(
        'create' => array(
        ),
    );
}
