<?php

namespace File\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel,
    Eva\Mvc\Exception;

class File extends AbstractModel
{
    protected $itemTableName = 'File\DbTable\Files';

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


    public function getFile($fileId = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($fileId)){
            $this->setItem(array(
                'id' => $fileId,
            ));
        }
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

    public function getFileList(array $map = array())
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

    public function createFiles()
    {
        $files = $this->files;
        $items = array();
        foreach($files as $key => $file){
            $this->file = $file;
            $this->createFile();
        }

        return true;
    }

    public function createFile($data = null)
    {
        $file = $this->getUploadFile();
        if(!$file || !$file['received']){
            return false;
        }

        $data['fileName'] = $file['name'];
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('create.pre');

        $itemId = $item->create();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->create();
            }
        }
        $this->trigger('create');
        $this->trigger('create.post');
        return $this->lastFileId = $itemId;
    }

    public function saveFile($data = null)
    {
        return $this->saveItem($data);
    }


    public function removeFile()
    {
        $this->trigger('remove.pre');

        $item = $this->getFile();
        $itemArray = $item->toArray(array(
            '*',
            'getFullPath()'
        ));

        $filePath = $itemArray['FullPath'];

        $item->remove();
        if(file_exists($filePath)){
            @unlink($filePath);
        }


        $this->trigger('remove');
        $this->trigger('remove.post');
        return true;
    
    }
}
