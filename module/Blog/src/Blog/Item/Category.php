<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Category extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Categories';

    protected $map = array(
        'create' => array(
            'getUrlName()',
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

    public function getCreateTime()
    {
        if(!$this->createTime) {
            return $this->createTime = \Eva\Date\Date::getNow();
        }
    }
}
