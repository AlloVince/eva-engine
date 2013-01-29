<?php

namespace Notification\Item;

use Eva\Mvc\Item\AbstractItem;

class Index extends AbstractItem
{
    protected $dataSourceClass = 'Notification\DbTable\Indexes';

    protected $map = array(
    );


}
