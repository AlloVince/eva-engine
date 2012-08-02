<?php

namespace Blog\Model\CategoryPost;

use Eva\Mvc\Model\AbstractItem,
    Eva\Api;

class Item extends AbstractItem
{
    public function getPostId()
    {
        $modelItem = $this->model->getItemArray();
        return $modelItem['id'];
    }

}
