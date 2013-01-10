<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class Tag extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\Tags';

    protected $map = array(
        'create' => array(
            'getParentId()',
        ),
        'save' => array(
        )
    );

    public function getParentId()
    {
        if(!$this->parentId) {
            return $this->parentId = 0;
        }
    }
}
