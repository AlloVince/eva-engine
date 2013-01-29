<?php

namespace Notification\Item;

use Eva\Mvc\Item\AbstractItem;

class Message extends AbstractItem
{
    protected $dataSourceClass = 'Notification\DbTable\Messages';

    protected $map = array(
    );


}
