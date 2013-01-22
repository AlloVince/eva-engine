<?php

namespace Event\Model;

use Album\Model\Album as AlbumModel;

class Album extends AlbumModel
{
    protected $itemClass = 'Event\Item\Album';
    protected $eventAlbumPaginator;

    public function getEventAlbumPaginator()
    {
        return $this->eventAlbumPaginator;
    }
    
    public function getEventAlbum($eventId = null, array $map = array())
    {
        $this->trigger('get.precache');
        
        $indexItem = $this->getItem('Event\Item\EventAlbum');
        $indexItem->collections(array(
            'event_id' => $eventId,
            'rows' => 1,
        ));
        
        if ($indexItem) {
            $indexItem = $indexItem->toArray();
        } else {
            return $this->getItem();
        }

        if (!isset($indexItem[0])) {
            return $this->getItem();
        }

        $albumId = $indexItem[0]['album_id'];

        $this->setItem(array(
            'id' => $albumId,
        ));

        $this->trigger('get.pre');

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }

        $this->trigger('get');

        $this->trigger('get.post');
        $this->trigger('get.postcache');

        return $item;
    }

    public function getEventAlbumList($params)
    {
        $indexItem = $this->getItem('Event\Item\EventAlbum');

        $defaultParams = array(
            'event_id' => '',
            'order' => 'iddesc',
            'page' => 1,
            'rows' => 20,
        );

        $itemQueryParams = array_merge($defaultParams, $params);

        $indexItem->collections($itemQueryParams);
        $this->eventAlbumPaginator = $indexItem->getPaginator();

        $albumIdArray = array();
        foreach($indexItem as $index){
            $albumIdArray[] = $index['album_id'];
        }
        if(!$albumIdArray){
            $this->setItemList(array(
                'noResult' => true
            ));
        } else {
            $this->setItemList(array(
                'id' => $albumIdArray,
                'order' => 'idarray',
                'noLimit' => true,
            ));
        }
        return $this;
    }

}
