<?php

namespace Notification\Model;

use Eva\Api;
use Eva\Mvc\Model\AbstractModel;
use User\Item\User;

class Notice extends AbstractModel
{

    public function markAsRead()
    {
    
    }

    public function getNoticeList(array $map = array())
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

    public function createNotice($data = null)
    {
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

        return $itemId;
    }


    public function removeNotice()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $item->remove();

        $this->trigger('remove');

        $this->trigger('remove.post');

        return true;
    }


}
