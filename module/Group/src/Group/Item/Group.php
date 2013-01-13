<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class Group extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\Groups';

    protected $relationships = array(
        'Text' => array(
            'targetEntity' => 'Group\Item\Text',
            'relationship' => 'OneToOne',
            'joinColumn' => 'group_id',
            'referencedColumn' => 'id',
        ),
        'Count' => array(
            'targetEntity' => 'Group\Item\Count',
            'relationship' => 'OneToOne',
            'joinColumn' => 'group_id',
            'referencedColumn' => 'id',
        ),
        'File' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'File',
            'joinColumns' => array(
                'joinColumn' => 'group_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Group\Item\GroupFile',
            'inversedMappedBy' => 'GroupFile',
            'inverseJoinColumns' => array(
                'joinColumn' => 'file_id',
                'referencedColumn' => 'id',
            ),
        ),
        'GroupFile' => array(
            'targetEntity' => 'Group\Item\GroupFile',
            'relationship' => 'OneToMany',
            'joinColumn' => 'group_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'User',
            'joinColumns' => array(
                'joinColumn' => 'group_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Group\Item\GroupUser',
            'inversedMappedBy' => 'GroupUser',
            'inverseJoinColumns' => array(
                'joinColumn' => 'user_id',
                'referencedColumn' => 'id',
            ),
        ),
        'GroupUser' => array(
            'targetEntity' => 'Group\Item\GroupUser',
            'relationship' => 'OneToMany',
            'joinColumn' => 'group_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'Category' => array(
            'targetEntity' => 'Group\Item\Category',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Category',
            'joinColumns' => array(
                'joinColumn' => 'group_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Group\Item\CategoryGroup',
            'inversedMappedBy' => 'CategoryGroup',
            'inverseJoinColumns' => array(
                'joinColumn' => 'category_id',
                'referencedColumn' => 'id',
            ),
        ),
        'CategoryGroup' => array(
            'targetEntity' => 'Group\Item\CategoryGroup',
            'relationship' => 'OneToMany',
            'joinColumn' => 'group_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'PostCount' => array(
            'targetEntity' => 'Group\Item\GroupPost',
            'relationship' => 'OneToMany',
            'joinColumn' => 'group_id',
            'referencedColumn' => 'id',
            'asCount' => true,
            'countKey' => 'postCount',
            'joinParameters' => array(
                'count' => true,
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getGroupKey()',
            'getCreateTime()',
            'getCreatorId()',
        ),
        'save' => array(
            'getGroupKey()',
        ),
    );

    public function getGroupKey()
    {
        if(!$this->groupKey) {
            return $this->groupKey = \Eva\Stdlib\String\Hash::uniqueHash();
        }
    }

    public function getCreateTime()
    {
        if(!$this->createTime) {
            return $this->createTime = \Eva\Date\Date::getNow();
        }
    }

    public function getCreatorId()
    {
        if(!$this->user_id){
            $user = \Core\Auth::getLoginUser();
            $this->user_id = $user['id'];
        }
    }
}
