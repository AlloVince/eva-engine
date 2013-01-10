<?php

namespace Album\Item;

use Eva\Mvc\Item\AbstractItem;

class CategoryAlbum extends AbstractItem
{
    protected $dataSourceClass = 'Album\DbTable\CategoriesAlbums';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create($mapKey = 'create')
    {
        $albumItem = $this->getModel()->getItem('Album\Item\Album');
        $albumId = $albumItem->id;
        if(!$albumId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(count($this) > 0){
            foreach($this as $item){
                $item['album_id'] = $albumId;
                $dataClass->create($item);
            }
        }
    }

    public function save($mapKey = 'save')
    {
        $albumItem = $this->getModel()->getItem('Album\Item\Album');
        $albumId = $albumItem->id;
        if(!$albumId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(count($this) > 0){
            foreach($this as $item){
                $item['album_id'] = $albumId;
                $dataClass->where(array(
                    'album_id' => $albumId,
                    'category_id' => $item['category_id'],
                ))->remove();
                $dataClass->create($item);
            }
        }
    }
}
