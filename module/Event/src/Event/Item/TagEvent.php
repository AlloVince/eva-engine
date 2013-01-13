<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class TagEvent extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\TagsEvents';

    protected $map = array();
}
