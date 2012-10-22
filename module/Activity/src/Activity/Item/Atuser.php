<?php

namespace Activity\Item;

use Eva\Mvc\Item\AbstractItem;

class Atuser extends AbstractItem
{
    protected $dataSourceClass = 'Activity\DbTable\Atusers';

    protected $map = array(
        'create' => array(
        ),
    );
}
