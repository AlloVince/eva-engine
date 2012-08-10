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

    protected $configKey;

    public function getConfigKey()
    {
        return $this->configKey;
    }

    public function setConfigKey($configKey)
    {
        $this->configKey = $configKey;
        return $this;
    }

    public function getLastfileId()
    {
        return $this->lastFileId;
    }

    public function setUploadFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    public function getUploadFiles()
    {
        return $this->files;
    }

    public function setUploadFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getUploadFile()
    {
        return $this->file;
    }


    public function getFile()
    {
        $params = $this->getItemParams();
        if(!$params || !is_numeric($params)){
            throw new \Core\Model\Exception\InvalidArgumentException(sprintf(
                '%s params %s not correct',
                __METHOD__,
                $params
            ));
        }


        $itemTable = $this->getItemTable();

        $this->item = $item = $itemTable->where(array('id' => $params))->find('one');

        if($item) {
            $this->item = $item = $this->setItemAttrMap(array(
                'Url' => array('configKey', 'getUrl'),
                'Thumb' => array('configKey', 'getThumb'),
                'ReadableFileSize' => array('fileSize', 'getReadableFileSize'),
                'description' => array('description', 'getDescription'),
            ))->getItemArray();
        }

        return $this->item = $item;
    }

    public function getFiles()
    {
        $defaultParams = array(
            'enableCount' => true,
            'keyword' => '',
            'status' => '',
            'fileExtension' => '',
            'isImage' => null,
            'fileSizeFrom' => '',
            'fileSizeTo' => '',
            'imageWidthFrom' => '',
            'imageWidthTo' => '',
            'imageHeightFrom' => '',
            'imageHeightTo' => '',
            'page' => 1,
            'order' => 'iddesc',
        );
        $params = $this->getItemListParams();
        $params = new \Zend\Stdlib\Parameters(array_merge($defaultParams, $params));

        $itemTable = $this->getItemTable();

        $itemTable->selectFiles($params);
        $items = $itemTable->find('all');

        return $this->itemList = $items;
    }

    public function createFiles()
    {
        $files = $this->files;
        $items = array();
        foreach($files as $key => $file){
            $this->file = $file;
            $this->item = array();
            $this->createFile();
        }

        return true;
    }

    public function createFile()
    {
        $file = $this->file;
        if(!$file || !$file['received']){
            return false;
        }

        $configKey = $this->getConfigKey();
        $globelConfig = Api::_()->getConfig();
        if(!$configKey || !isset($globelConfig['upload']['storage'][$configKey])){
            throw new Exception\InvalidArgumentException(printf(
                'No file config key found.'
            ));
        }

        $item = $this->setItemAttrMap(array(
            'title' => array('title', 'getTitle'),
            'status' => array('status', 'getStatus'),
            'isImage' => array('isImage', 'getIsImage'),
            'configKey' => array('configKey', 'getConfigKey'),
            'fileName' => array('fileName', 'getFileName'),
            'fileExtension' => array('fileExtension', 'getFileExtension'),
            'originalName' => array('originalName', 'getOriginalName'),
            'fileServerKey' => array('fileServerKey', 'getServerKey'),
            'fileServerName' => array('fileServerName', 'getServerName'),
            'filePath' => array('filePath', 'getFilePath'),
            'fileHash' => array('fileHash', 'getFileHash'),
            'fileSize' => array('fileSize', 'getFileSize'),
            'imageWidth' => array('imageWidth', 'getImageWidth'),
            'imageHeight' => array('imageHeight', 'getImageHeight'),
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
