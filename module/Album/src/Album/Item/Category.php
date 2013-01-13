<?php

namespace Album\Item;

use Eva\Mvc\Item\AbstractItem;

class Category extends AbstractItem
{
    protected $dataSourceClass = 'Album\DbTable\Categories';

    protected $map = array(
        'create' => array(
            'getUrlName()',
            'getParentId()',
            'getCreateTime()',
        ),
        'save' => array(
            'getUrlName()',
        )
    );

    public function getUrlName()
    {
        if(!$this->urlName) {
            return $this->urlName = \Eva\Stdlib\String\Hash::uniqueHash();
        }
    }
    
    public function getParentId()
    {
        if(!$this->parentId) {
            return $this->parentId = 0;
        }
    }

    public function getCreateTime()
    {
        if(!$this->createTime) {
            return $this->createTime = \Eva\Date\Date::getNow();
        }
    }
}
