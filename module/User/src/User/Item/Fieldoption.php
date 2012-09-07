<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Fieldoption extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Fieldoptions';

    protected $relationships = array(

    );

    protected $map = array(
        'create' => array(
        ),
    );
}
