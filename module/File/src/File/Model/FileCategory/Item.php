<?php

namespace File\Model\FileCategory;

use Eva\Mvc\Model\AbstractItem,
    Eva\Api;

class Item extends AbstractItem
{
    public function getCategoryId()
    {
        $modelItem = $this->model->getItemArray();
        return $modelItem['id'];
    }

}
