<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Post extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Posts';

    protected $inverseRelationships = array(
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'user_id',
            'joinParameters' => array(
            ),
        ),
        'UserPostCount' => array(
            'targetEntity' => 'Blog\Item\Post',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'asCount' => true,
            'countKey' => 'postCount',
            'joinParameters' => array(
                'count' => true,
            ),
        ),
    );

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
            'getUserId()',
            'getUserName()',
            'getEditorId()',
            'getEditorName()',
        ),
        'save' => array(
            'getUrlName()',
            'getUpdateTime()',
            'getEditorId()',
            'getEditorName()',
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

    public function getUserId()
    {
        if(!$this->user_id){
            $user = \Core\Auth::getLoginUser();
            $this->user_id = $user['id'];
        }
    }

    public function getUserName()
    {
        if(!$this->user_name){
            $user = \Core\Auth::getLoginUser();
            $this->user_name = $user['userName'];
        }
    }

    public function getEditorId()
    {
        $user = \Core\Auth::getLoginUser();
        $this->editor_id = $user['id'];
    }

    public function getEditorName()
    {
        $user = \Core\Auth::getLoginUser();
        $this->editor_name = $user['userName'];
    }

}
