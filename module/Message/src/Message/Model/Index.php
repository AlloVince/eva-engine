<?php

namespace Message\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Index extends AbstractModel
{
    protected $itemTableName = 'Message\DbTable\Indexes';

    public function getIndexList(array $map = array())
    {
        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        return $item;
    }
}
