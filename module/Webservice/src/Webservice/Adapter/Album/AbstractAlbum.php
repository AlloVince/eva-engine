<?php
    
namespace Webservice\Adapter\Album;

use Webservice\Adapter\AbstractUniform;
use Webservice\Exception;

abstract class AbstractAlbum extends AbstractUniform implements AlbumInterface
{
    protected $photoId;

    protected $albumId;

    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;
        return $this;
    }

    public function setPhotoId($photoId)
    {
        $this->photo = $photoId;
        return $this;
    }

    public function getUploadStatus()
    {
    }

    public function isAbleToUpload()
    {
        return true;
    }

    public function getPhoto($photoId = null)
    {
        return $this->getData('Photo');
    }

    public function getPhotoList($start, $rows = 10)
    {
        return $this->getData('PhotoList');
    }

    public function uploadPhoto($params)
    {
    }

    public function updatePhoto($params)
    {
    
    }

    public function remotePhoto()
    {
    
    }

    public function getAlbumList()
    {
    }

    public function getAlbum()
    {
    }

    public function createAlbum($params)
    {
    }
    
    public function updateAlbum($params)
    {
    }

    public function removeAlbum($params)
    {
    }



}
