<?php

namespace Core\Item;

use Eva\Mvc\Item\AbstractItem;

class Newsletter extends AbstractItem
{
    protected $dataSourceClass = 'Core\DbTable\Newsletters';
}
