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
        $file = $this->model->getUploadFile();
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

    public function getFullPath()
    {
        $item = $this->item;

        $configKey = $item['configKey'] ? $item['configKey'] : 'default';
        $config = Api::_()->getConfig();
        $dir = str_replace(array('/', '\\'), '/', $item['filePath']);
        $dir = trim($dir, '/\\');
        $domain = '';
        $dirPrefix = '';
        if(isset($config['upload']['storage'][$configKey])){
            $config = $config['upload']['storage'][$configKey];
            $rootpath = $config['rootpath'];
            $urlroot = $config['urlroot'];
            $domain = $config['domain'];

            $dirPrefix = str_replace($urlroot, '', $rootpath);
            if($dirPrefix){
                $dirPrefix = trim($dirPrefix, '/\\');
                $dirPrefix = str_replace(array('/', '\\'), '/', $dirPrefix);
            }
        }

        $url = $domain ? 'http://' . $domain . '/' : '/'; 
        $url .= $dirPrefix . '/' . $dir . '/' . $item['fileName'];
        return $url;
    }

    public function getUrl()
    {
        $item = $this->item;

        $configKey = $item['configKey'] ? $item['configKey'] : 'default';
        $config = Api::_()->getConfig();
        $dir = str_replace(array('/', '\\'), '/', $item['filePath']);
        $dir = trim($dir, '/\\');
        $domain = '';
        $dirPrefix = '';
        if(isset($config['upload']['storage'][$configKey])){
            $config = $config['upload']['storage'][$configKey];
            $rootpath = $config['rootpath'];
            $urlroot = $config['urlroot'];
            $domain = $config['domain'];

            $dirPrefix = str_replace($urlroot, '', $rootpath);
            if($dirPrefix){
                $dirPrefix = trim($dirPrefix, '/\\');
                $dirPrefix = str_replace(array('/', '\\'), '/', $dirPrefix);
            }
        }

        $url = $domain ? 'http://' . $domain . '/' : '/'; 
        $url .= $dirPrefix . '/' . $dir . '/' . $item['fileName'];
        return $url;
    }

    public function getThumb()
    {
        $item = $this->item;
        $configKey = $item['configKey'] ? $item['configKey'] : 'default';
        $config = Api::_()->getConfig();
        if(!isset($config['upload']['storage'][$configKey]['thumburl'])){
            return '';
        }

        $thumb = $config['upload']['storage'][$configKey]['thumburl'] .  str_replace(array('/', '\\'), '/', $item['filePath']) . '/' . $item['fileName'];
        return $thumb;
    }

    public function getConfigKey()
    {
        return $this->model->getConfigKey();
    }

    public function getReadableFileSize()
    {
        $item = $this->item;
        $size = $item['fileSize'];
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2).$units[$i];
    }



    public function getStatus($status)
    {
        if(!$status){
            return 'published';
        }
        return $status;
    }



    public function getIsImage()
    {
        $file = $this->model->getUploadFile();
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
        $file = $this->model->getUploadFile();
        return $file['name'];
    }

    public function getFileExtension($fileExtension)
    {
        $file = $this->model->getUploadFile();
        if($file['name']) {
            return strtolower(end(explode(".", $file['name'])));
        }
    }

    public function getOriginalName($originalName)
    {
        $file = $this->model->getUploadFile();
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
        $file = $this->model->getUploadFile();
        //Hash file small than 10M
        if($file['size'] < 1048576 * 10){
            return hash_file('CRC32', $file['tmp_name'], false);
        }
        return $fileHash;
    }


    public function getFileSize($fileSize)
    {
        $file = $this->model->getUploadFile();
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
