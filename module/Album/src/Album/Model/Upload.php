<?php

namespace Album\Model;

class Upload extends \File\Model\File
{
    protected $itemClass = 'File\Item\File';

    protected $album;

    public function setAlbum($album)
    {
        $this->album = $album;
        return $this;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function createFiles()
    {
        $this->getEvent()->attach('album.model.upload.create.post', array($this, 'connectFileToAlbum'));
        $this->getEvent()->attach('album.model.upload.create.post', array($this, 'setAlbumCover'));
        return parent::createFiles();
    }

    public function connectFileToAlbum($event)
    {
        $album = $this->getAlbum();
        $albumId = $album['id'];
        $item = $this->getItem();
        $albumFileItem = $this->getItem('Album\Item\AlbumFile');
        $albumFileItem->album_id = $albumId;
        $albumFileItem->file_id = $item->id;
        $albumFileItem->create();
    }

    public function setAlbumCover($event)
    {
        $album = $this->getAlbum();
        
        if ($album['cover_id']) {
            return;
        }
        
        $item = $this->getItem();
        $album->cover_id = $item->id;
        $album->save();
    }
}
