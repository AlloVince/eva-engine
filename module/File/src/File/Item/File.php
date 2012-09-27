<?php

namespace File\Item;

use Eva\Mvc\Item\AbstractItem;
use Eva\Mvc\Exception;
use Eva\Api;

class File extends AbstractItem
{
    private $image;

    protected $dataSourceClass = 'File\DbTable\Files';

    protected $inverseRelationships = array(
        'PostCover' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'PostCover',
            'joinColumns' => array(
                'joinColumn' => 'connect_id',
                'referencedColumn' => 'id',
                'joinParameters' => array(
                    'connectType' => 'PostCover',
                ),
            ),
            'inversedBy' => 'File\Item\FileConnect',
            'inversedMappedBy' => 'FileConnect',
            'inverseJoinColumns' => array(
                'joinColumn' => 'file_id',
                'referencedColumn' => 'id',
            ),
        ),
    );

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

    protected $map = array(
        'create' => array(
            'getCreateTime()',
            'getStatus()',
            'getConfigKey()',
            'getFileName()',
            'getFileExtension()',
            'getOriginalName()',
            'getFilePath()',
            'getFileHash()',
            'getFileSize()',
            'getTitle()',
            'getIsImage()',
            'getImageWidth()',
            'getImageHeight()',
        ),
        'save' => array(
            'getStatus()',
        ),
    );

    public function getTitle()
    {
        $file = $this->getModel()->getUploadFile();
        $fileName = isset($file['original_name']) && $file['original_name'] ? $file['original_name'] : $file['name'];
        $nameArray = explode(".", $fileName);
        if(count($nameArray) == 1){
            return $this->title = $fileName;
        }
        array_pop($nameArray);
        return $this->title = implode(".", $nameArray);
    }

    public function getStatus()
    {
        if(!$this->status){
            return $this->status = 'published';
        }
    }

    public function getConfigKey()
    {
        $configKey = $this->getModel()->getConfigKey();
        if(!$configKey){
            throw new Exception\InvalidArgumentException(sprintf(
                'No upload file config key found in %s', get_class($this)
            )); 
        }
        return $this->configKey = $configKey;
    }

    public function getFileName()
    {
        $file = $this->getModel()->getUploadFile();
        return $this->fileName = $file['name'];
    }

    public function getFileExtension()
    {
        $file = $this->getModel()->getUploadFile();
        if($file['name']) {
            return $this->fileExtension = strtolower(end(explode(".", $file['name'])));
        }
    }

    public function getOriginalName()
    {
        $file = $this->getModel()->getUploadFile();
        $fileName = isset($file['original_name']) && $file['original_name'] ? $file['original_name'] : $file['name'];
        return $this->originalName = $fileName;
    }

    public function getFilePath()
    {
        return $this->filePath = $this->mergePath();
    }


    public function getFileHash()
    {
        $file = $this->getModel()->getUploadFile();
        //Hash file small than 10M
        if($file['size'] < 1048576 * 10){
            return $this->fileHash = hash_file('CRC32', $file['tmp_name'], false);
        }
    }


    public function getFileSize()
    {
        $file = $this->getModel()->getUploadFile();
        return $this->fileSize = $file['size'];
    }

    public function getIsImage()
    {
        $file = $this->getModel()->getUploadFile();
        $this->image = $image = getimagesize($file['tmp_name']);
        if(false === $image){
            return $this->isImage = '0';
        }
        return $this->isImage = '1';
    }

    public function getImageWidth()
    {
        if($this->image){
            $this->imageWidth = $this->image[0];
        }
    }


    public function getImageHeight()
    {
        if($this->image){
            $this->imageHeight = $this->image[1];
        }
    }


    public function getReadableFileSize()
    {
        $size = $this->fileSize;
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return $this->ReadableFileSize = round($size, 2) . $units[$i];
    }

    public function getFullPath()
    {
        $configKey = $this->configKey ?  $this->configKey : 'default';
        $config = Api::_()->getConfig();
        $dir = str_replace(array('/', '\\'), '/', $this->filePath);
        $dir = trim($dir, '/\\');
        $domain = '';
        $dirPrefix = '';
        if(isset($config['upload']['storage'][$configKey])){
            $config = $config['upload']['storage'][$configKey];
            $rootpath = $config['rootpath'];
            $domain = $config['domain'];
        }

        $path = $rootpath . \DIRECTORY_SEPARATOR .  $this->filePath . \DIRECTORY_SEPARATOR . $this->fileName;
        return $this->FullPath = $path;
    }

    public function getUrl()
    {
        $configKey = $this->configKey ?  $this->configKey : 'default';
        $config = Api::_()->getConfig();
        $dir = str_replace(array('/', '\\'), '/', $this->filePath);
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
        $url .= $dirPrefix . '/' . $dir . '/' . $this->fileName;
        return $this->Url = $url;
    }

    public function getThumb()
    {
        $configKey = $this->configKey ?  $this->configKey : 'default';
        $config = Api::_()->getConfig();
        if(!isset($config['upload']['storage'][$configKey]['thumburl'])){
            return '';
        }

        $thumb = $config['upload']['storage'][$configKey]['thumburl'] .  str_replace(array('/', '\\'), '/', $this->filePath) . '/' . $this->fileName;
        return $this->Thumb = $thumb;
    }


    public function getCreateTime()
    {
        if(!$this->createTime) {
            return $this->createTime = \Eva\Date\Date::getNow();
        }
    }

    protected function mergePath()
    {
        $config = Api::_()->getConfig();
        $file = $this->getModel()->getUploadFile();
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

}
