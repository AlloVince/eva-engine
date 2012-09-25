<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Post extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Posts';

    protected $relationships = array(
        'Text' => array(
            'targetEntity' => 'Blog\Item\Text',
            'relationship' => 'OneToOne',
            'joinColumn' => 'post_id',
            'referencedColumn' => 'id',
        ),
        'Categories' => array(
            'targetEntity' => 'Blog\Item\Category',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Categories',
            'joinColumns' => array(
                'joinColumn' => 'post_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Blog\Item\CategoryPost',
            'inversedMappedBy' => 'CategoryPost',
            'inverseJoinColumns' => array(
                'joinColumn' => 'category_id',
                'referencedColumn' => 'id',
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getUrlName()',
            'getCreateTime()',
            'getUpdateTime()',
        ),
        'save' => array(
            'getUrlName()',
            'getUpdateTime()',
        ),
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

    public function getUpdateTime()
    {
        $this->updateTime = \Eva\Date\Date::getNow();
    }
}
