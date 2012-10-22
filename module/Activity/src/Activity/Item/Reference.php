<?php

namespace Activity\Item;

use Eva\Mvc\Item\AbstractItem;

class Reference extends AbstractItem
{
    protected $dataSourceClass = 'Activity\DbTable\References';

    protected $map = array(
        'create' => array(
        ),
    );
}
