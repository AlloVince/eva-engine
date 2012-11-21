<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class Text extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\Texts';

    protected $map = array(
        'create' => array(
        ),
    );

    public function getPreview()
    {
        if(!$this->Preview) {
            return $this->Preview = strip_tags($this->content);
        }
    }
}
