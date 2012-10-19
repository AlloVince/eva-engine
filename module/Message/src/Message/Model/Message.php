<?php

namespace Message\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Message extends AbstractModel
{
    protected $itemTableName = 'Message\DbTable\Messages';

    public function getMessageList(array $map = array())
    {
        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        return $item;
    }

    public function createMessage($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $itemId = $item->create();
        
        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->create();
            }
        }

        return $itemId;
    }
}
