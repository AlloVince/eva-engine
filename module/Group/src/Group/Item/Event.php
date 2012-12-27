<?php

namespace Group\Item;

class Event extends \Blog\Item\Post
{
    protected $dataSourceClass = 'Group\DbTable\Events';

    protected $addRelationships = array(
        'Group' => array(
            'targetEntity' => 'Group\Item\Group',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Group',
            'joinColumns' => array(
                'joinColumn' => 'event_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Group\Item\GroupEvent',
            'inversedMappedBy' => 'GroupEvent',
            'inverseJoinColumns' => array(
                'joinColumn' => 'group_id',
                'referencedColumn' => 'id',
            ),
        ),
        'GroupEvent' => array(
            'targetEntity' => 'Group\Item\GroupEvent',
            'relationship' => 'OneToMany',
            'joinColumn' => 'event_id',
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
