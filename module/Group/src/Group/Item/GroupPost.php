<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class GroupPost extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\GroupsPosts';
}
