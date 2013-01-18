<?php

namespace Album\Item;

use Eva\Mvc\Item\AbstractItem;

class AlbumFile extends AbstractItem
{
    protected $dataSourceClass = 'Album\DbTable\AlbumsFiles';

    protected $inverseRelationships = array(
        'Image' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'file_id',
            'joinParameters' => array(
            ),
        ),
    );

    protected $map = array(
        'create' => array(
        ),
    );
}
