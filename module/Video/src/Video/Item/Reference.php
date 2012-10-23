<?php

namespace Video\Item;

use Eva\Mvc\Item\AbstractItem;

class Reference extends AbstractItem
{
    protected $dataSourceClass = 'Video\DbTable\References';

    protected $map = array(
        'create' => array(
        ),
    );
}
