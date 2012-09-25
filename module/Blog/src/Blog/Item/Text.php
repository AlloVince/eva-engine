<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Text extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Texts';

    protected $map = array(
        'create' => array(
        ),
    );

    public function getContentHtml()
    {
    }
}
