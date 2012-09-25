<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class CategoryPost extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\CategoriesPosts';

    protected $map = array(
        'create' => array(
        ),
    );
}
