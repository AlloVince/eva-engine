<?php

namespace Notification\Item;

use Eva\Mvc\Item\AbstractItem;

class MessageUser extends AbstractItem
{
    protected $dataSourceClass = 'Notification\DbTable\MessagesUsers';

    protected $map = array(
    );


}
