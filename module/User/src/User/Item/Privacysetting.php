<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Privacysetting extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Privacysettings';

    protected $map = array(
    );
}
