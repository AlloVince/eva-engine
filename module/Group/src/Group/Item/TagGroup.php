<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class TagGroup extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\TagsGroups';

    protected $map = array();
}
