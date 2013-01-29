<?php

namespace Notification\Item;

use Eva\Mvc\Item\AbstractItem;

class Notice extends AbstractItem
{
    protected $dataSourceClass = 'Notification\DbTable\Notices';

    protected $map = array(
    );
}
