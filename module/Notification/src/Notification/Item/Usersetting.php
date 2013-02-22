<?php

namespace Notification\Item;

use Eva\Mvc\Item\AbstractItem;

class Usersetting extends AbstractItem
{
    protected $dataSourceClass = 'Notification\DbTable\Usersettings';

    protected $map = array(
    );
}
