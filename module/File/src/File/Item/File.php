<?php

namespace File\Item;

use Eva\Mvc\Item\AbstractItem;

class File extends AbstractItem
{
    protected $dataSourceClass = 'File\DbTable\Files';

    protected $relationships = array(
        'UserAvatar' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Avatar',
            'joinColumns' => array(
                'joinColumn' => 'file_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'File\Item\Avatar',
            'inverseJoinColumns' => array(
                'joinColumn' => 'user_id',
                'referencedColumn' => 'id',
            ),
        ),
    );
}
