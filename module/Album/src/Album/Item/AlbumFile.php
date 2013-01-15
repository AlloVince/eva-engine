<?php

namespace Album\Item;

use Eva\Mvc\Item\AbstractItem;

class AlbumFile extends AbstractItem
{
    protected $dataSourceClass = 'Album\DbTable\AlbumsFiles';

    protected $map = array(
        'create' => array(
        ),
    );
}
