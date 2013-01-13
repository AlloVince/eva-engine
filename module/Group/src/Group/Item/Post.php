<?php

namespace Group\Item;

class Post extends \Blog\Item\Post
{
    protected $dataSourceClass = 'Group\DbTable\Posts';

    protected $addRelationships = array(
        'Group' => array(
            'targetEntity' => 'Group\Item\Group',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Group',
            'joinColumns' => array(
                'joinColumn' => 'post_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Group\Item\GroupPost',
            'inversedMappedBy' => 'GroupPost',
            'inverseJoinColumns' => array(
                'joinColumn' => 'group_id',
                'referencedColumn' => 'id',
            ),
        ),
        'GroupPost' => array(
            'targetEntity' => 'Group\Item\GroupPost',
            'relationship' => 'OneToMany',
            'joinColumn' => 'post_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
    );

    public function __construct()
    {
        if ($this->addRelationships) {
            foreach ($this->addRelationships as $key=>$addRelationship) {
                $this->addRelationship($key, $addRelationship);
            }
        }
    }
}
