<?php

namespace File\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class File extends AbstractModel
{
    protected $itemTableName = 'File\DbTable\Files';

    protected $files = array();
    protected $file;
    protected $lastFileId;

    public function getLastfileId()
    {
        return $this->lastFileId;
    }

    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function saveFiles()
    {
        $files = $this->files;
        $items = array();
        foreach($files as $key => $file){
            $this->file = $file;
            $this->item = array();
            $this->saveFile();
        }

        return true;
    }

    public function saveFile()
    {
        $file = $this->file;
        if(!$file || !$file['received']){
            return false;
        }

        $item = $this->setItemAttrMap(array(
            'title' => array('title', 'getTitle'),
            'isImage' => array('isImage', 'getIsImage'),
            'fileName' => array('fileName', 'getFileName'),
            'fileExtension' => array('fileExtension', 'getFileExtension'),
            'originalName' => array('originalName', 'getOriginalName'),
            'serverKey' => array('serverKey', 'getServerKey'),
            'serverName' => array('serverName', 'getServerName'),
            'filePath' => array('filePath', 'getFilePath'),
            'fileHash' => array('fileHash', 'getFileHash'),
            'fileSize' => array('fileSize', 'getFileSize'),
            'imageWidth' => array('imageWidth', 'getImageWidth'),
            'imageHeight' => array('imageHeight', 'getImageHeight'),
            'description' => array('description', 'getDescription'),
            'user_id' => array('user_id', 'getUserId'),
            'user_name' => array('user_name', 'getUserName'),
            'createTime' => array('createTime', 'getCreateTime'),
        ))->getItemArray();

        $itemTable = $this->getItemTable();
        $itemTable->create($item);
        $this->lastFileId = $itemTable->getLastInsertValue();

        return $this->lastFileId;
    }
}
