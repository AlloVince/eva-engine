<?php

namespace File\Item;

use Eva\Mvc\Item\AbstractItem;

class FileConnect extends AbstractItem
{
    protected $dataSourceClass = 'File\DbTable\FilesConnections';



    public function save()
    {
        $dataClass = $this->getDataClass();
        if($this->connect_id && $this->connectType){
            $dataClass->where(array(
                'connect_id' => $this->connect_id,
                'connectType' => $this->connectType,
            ))->remove();
            if($this->file_id){
                $dataClass->create($this->toArray());
            }
        }
    }
}
