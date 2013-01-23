<?php

namespace Group\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class GroupEvent extends AbstractModel
{
    protected $itemTableName = 'Group\DbTable\GroupsEvents';
    
    public function getGroupEventList(array $itemListParameters = array(), $map = null)
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
}
