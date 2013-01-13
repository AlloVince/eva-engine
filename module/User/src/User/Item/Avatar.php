<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Avatar extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Avatars';
}
