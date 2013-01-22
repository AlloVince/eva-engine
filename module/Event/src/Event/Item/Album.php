<?php

namespace Event\Item;

class Album extends \Album\Item\Album
{
    protected $dataSourceClass = 'Event\DbTable\Albums';

    protected $addRelationships = array(
        'Event' => array(
            'targetEntity' => 'Event\Item\Event',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Event',
            'joinColumns' => array(
                'joinColumn' => 'album_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Event\Item\EventAlbum',
            'inversedMappedBy' => 'EventAlbum',
            'inverseJoinColumns' => array(
                'joinColumn' => 'event_id',
                'referencedColumn' => 'id',
            ),
        ),
        'EventAlbum' => array(
            'targetEntity' => 'Event\Item\EventAlbum',
            'relationship' => 'OneToMany',
            'joinColumn' => 'album_id',
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
