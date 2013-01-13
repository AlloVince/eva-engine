<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class ImageUser extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\ImagesUsers';


    public function save($mapKey = 'save')
    {
        //change image must have a usage
        if(!$this->usage || !$this->user_id){
            return;
        }
        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'user_id' => $this->user_id,
            'usage' => $this->usage,
        ))->remove();
        if($this->file_id){
            $dataClass->create($this->toArray());
        }
    }
}
