<?php

namespace Notification\Item;

use Eva\Mvc\Item\AbstractItem;

class Notification extends AbstractItem
{
    protected $dataSourceClass = 'Notification\DbTable\Notifications';

    protected $map = array(
    );


}
