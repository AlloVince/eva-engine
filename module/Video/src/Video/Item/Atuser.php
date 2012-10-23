<?php

namespace Video\Item;

use Eva\Mvc\Item\AbstractItem;

class Atuser extends AbstractItem
{
    protected $dataSourceClass = 'Video\DbTable\Atusers';

    protected $map = array(
        'create' => array(
        ),
    );
}
