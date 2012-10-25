<?php

namespace Message\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Conversation extends AbstractModel
{
    protected $itemTableName = 'Message\DbTable\Conversations';

    public function getConversationList(array $map = array())
    {
        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        return $item;
    }
    
    public function markAsRead($items = array())
    {
        if (!$items) {
            return;
        }
        
        foreach ($items as $item) {
            if ($item->readFlag == 1) {
                continue;
            }
            
            $item->readFlag = 1;
            $item->save();
        }
    }

    public function createConversation($data = null)
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

    public function removeConversation()
    {

        $item = $this->getItem();

        $item->remove();

        return true;
    
    }
}
