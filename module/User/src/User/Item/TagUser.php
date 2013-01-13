<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class TagUser extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\TagsUsers';

    protected $map = array(
    );
}
