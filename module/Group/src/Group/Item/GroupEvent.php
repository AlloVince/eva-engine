<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class GroupEvent extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\GroupsEvents';
}
