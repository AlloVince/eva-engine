<?php

namespace File\Model\FileConnect;

use Eva\Mvc\Model\AbstractItem,
    Eva\Api;

class Item extends AbstractItem
{
    public function getConnectId()
    {
        $modelItem = $this->model->getItemArray();
        return $modelItem['id'];
    }

    public function getConnectType()
    {
        $modelItem = $this->model->getItemArray();
        
        if ($this->model instanceof \Blog\Model\Category) {
            $connectType = 'category';
        } else if ($this->model instanceof \Blog\Model\Post) {
            $connectType = 'post';
        } else {
            $connectType = 'category';
        }
        
        return $connectType;
    }
}
