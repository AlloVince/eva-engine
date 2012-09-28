<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Code extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Codes';

    protected $relationships = array(
    );
}
