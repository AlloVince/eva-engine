<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class EventActivity extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\EventsActivities';
}
