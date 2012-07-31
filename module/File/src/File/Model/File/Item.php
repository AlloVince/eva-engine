<?php

namespace File\Model\File;

use Eva\Mvc\Model\AbstractItem,
    Eva\Api;

class Item extends AbstractItem
{
    protected $image;
    protected function mergePath()
    {
        $config = Api::_()->getConfig();
        $file = $this->model->getFile();
        if(isset($config['upload']['storage']['default'])){
            $rootpath = $config['upload']['storage']['default']['rootpath'];
            $rootpath = str_replace(array('/', '\\'), \DIRECTORY_SEPARATOR, $rootpath);

            $destination = str_replace(array('/', '\\'), \DIRECTORY_SEPARATOR, $file['destination']);
            $path = str_replace($rootpath, '', $destination);
            $path = trim($path, '/\\');

            return $path;
        }

        return $file['destination'];
    }

    public function getTitle($title)
    {
        $file = $this->model->getFile();
        $fileName = isset($file['original_name']) && $file['original_name'] ? $file['original_name'] : $file['name'];
        $nameArray = explode(".", $fileName);
        if(count($nameArray) == 1){
            return $fileName;
        }
        array_pop($nameArray);
        return implode(".", $nameArray);
    }


    public function getIsImage()
    {
        $file = $this->model->getFile();
        if($this->image){
            return 1;
        }
        $image = getimagesize($file['tmp_name']);
        if(false === $image){
            $this->image = false;
            return 0;
        }

        $this->image = $image;
        return 1;
    }


    public function getFileName($fileName)
    {
        $file = $this->model->getFile();
        return $file['name'];
    }

    public function getFileExtension($fileExtension)
    {
        $file = $this->model->getFile();
        if($file['name']) {
            return strtolower(end(explode(".", $file['name'])));
        }
    }

    public function getOriginalName($originalName)
    {
        $file = $this->model->getFile();
        $fileName = isset($file['original_name']) && $file['original_name'] ? $file['original_name'] : $file['name'];
        return $fileName;
    }


    public function getServerKey($serverKey)
    {
        return $serverKey;
    }


    public function getServerName($serverName)
    {
        return $serverName;
    }


    public function getFilePath($filePath)
    {
        return $this->mergePath();
    }


    public function getFileHash($fileHash)
    {
        $file = $this->model->getFile();
        //Hash file small than 10M
        if($file['size'] < 1048576 * 10){
            return hash_file('CRC32', $file['tmp_name'], false);
        }
        return $fileHash;
    }


    public function getFileSize($fileSize)
    {
        $file = $this->model->getFile();
        return $file['size'];
    }


    public function getImageWidth($imageWidth)
    {
        if(false === $this->image){
            return;
        }
        if(!$this->image && !$this->getIsImage()){
            return;
        }
        return $this->image[0];
    }


    public function getImageHeight($imageHeight)
    {
        if(false === $this->image){
            return;
        }
        if(!$this->image && !$this->getIsImage()){
            return;
        }
        return $this->image[1];
    }


    public function getDescription($description)
    {
        return $description;
    }


    public function getUserId($userId)
    {
        return $userId;
    }


    public function getUserName($userName)
    {
        return $userName;
    }


    public function getCreateTime($createTime)
    {
        return \Eva\Date\Date::getNow();
    }
}
