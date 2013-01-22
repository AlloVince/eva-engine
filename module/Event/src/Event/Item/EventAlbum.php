<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class EventAlbum extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\EventsAlbums';
}
