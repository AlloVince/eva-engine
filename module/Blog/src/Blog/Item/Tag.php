<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Tag extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Tags';

    protected $map = array(
    );
}
