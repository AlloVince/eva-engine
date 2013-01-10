<?php

namespace Album\Item;

use Eva\Mvc\Item\AbstractItem;

class Count extends AbstractItem
{
    protected $dataSourceClass = 'Album\DbTable\Counts';

    protected $map = array(
        'create' => array(
        ),
    );
}
