<?php

namespace Album\Item;

use Eva\Mvc\Item\AbstractItem;

class AlbumFile extends AbstractItem
{
    protected $dataSourceClass = 'Album\DbTable\AlbumsFiles';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create($mapKey = 'create')
    {
        $albumItem = $this->getModel()->getItem();
        $albumId = $albumItem->id;
        if(!$albumId || !$this->file_id) {
            return;
        }

        $data = $this->toArray();
        $data['album_id'] = $albumId;
        $dataClass = $this->getDataClass();
        $dataClass->create($data);
    }

    public function save($mapKey = 'save')
    {
        $albumItem = $this->getModel()->getItem();
        $albumId = $albumItem->id;
        if(!$albumId || !$this->file_id) {
            return;
        }

        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'album_id' => $albumId
        ))->remove();
        $data = $this->toArray();
        $saveData['album_id'] = $albumId;
        $saveData['file_id'] = $data['file_id'];
        $dataClass->create($saveData);
    }
}
