<?php

namespace Album\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class AlbumFile extends AbstractModel
{
    protected $itemTableName = 'Album\DbTable\AlbumsFiles';
    
    public function getAlbumFile($albumId = null, $fileId = null, array $map = array())
    {
        $this->setItem(array(
            'album_id' => $albumId,
            'file_id' => $fileId,
        ));

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }
        return $item;
    }

    public function getAlbumFileList(array $itemListParameters = array(), $map = null)
    {
        $this->trigger('list.precache');

        $this->trigger('list.pre');

        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        $this->trigger('get');

        $this->trigger('list.post');
        $this->trigger('list.postcache');

        return $item;
    }

    public function removeAlbumFile()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $item->remove();

        $this->trigger('remove');

        $this->trigger('remove.post');

        return true;

    }
}
