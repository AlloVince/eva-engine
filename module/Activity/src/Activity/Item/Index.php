<?php

namespace Activity\Item;

use Eva\Mvc\Item\AbstractItem;

class Index extends AbstractItem
{
    protected $dataSourceClass = 'Activity\DbTable\Indexes';

    protected $map = array(
        'create' => array(
        ),
    );
}
