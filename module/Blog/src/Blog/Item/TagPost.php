<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class TagPost extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\TagsPosts';

    protected $map = array(
    );
}
