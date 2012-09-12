<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Fieldvalue extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Fieldvalues';

    protected $relationships = array(
    );

    protected $map = array(
        'create' => array(
        ),
    );
}
