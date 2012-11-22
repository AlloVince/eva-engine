<?php

namespace Event\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Event extends AbstractModel
{
    protected $itemTableName = 'Event\DbTable\Events';

    protected $userList;

    public function setUserList($userList)
    {
        $this->userList = $userList;
        return $this;
    }

    public function getUserList($itemParams = array(), array $map = array())
    {
        if($this->userList){
            return $this->userList;
        }

        if(!Api::_()->isModuleLoaded('User')){
            return array();
        }

        $itemList = $this->getItemList();
        $idArray = array();

        foreach($itemList as $item){
            $idArray[] = $item['user_id'];
        }

        $userModel = Api::_()->getModel('User\Model\User');
        if(!$idArray){
            $itemParams['noResult'] = true;
        } else {
            $itemParams['id'] = $idArray;
        }
        $userModel->setItemList($itemParams);
        $userList = $userModel->getUserList($map);
        return $this->userList = $userList;
    }


    public function getEventdata($eventIdOrUrlName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($eventIdOrUrlName)){
            $this->setItem(array(
                'id' => $eventIdOrUrlName,
            ));
        } elseif(is_string($eventIdOrUrlName)) {
            $this->setItem(array(
                'urlName' => $eventIdOrUrlName,
            ));
        }
        $this->trigger('get.pre');

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }

        $this->trigger('get');

        $this->trigger('get.event');
        $this->trigger('get.eventcache');

        return $item;
    }

    public function getEventdataList(array $map = array())
    {
        $this->trigger('list.precache');

        $this->trigger('list.pre');

        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        $this->trigger('get');

        $this->trigger('list.event');
        $this->trigger('list.eventcache');

        return $item;
    }

    public function createEventdata($data = null)
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
        
        $eventUserItem = $this->getItem('Event\Item\EventUser');
        $eventUserItem->event_id = $itemId;
        $eventUserItem->user_id  = $item->user_id;
        $eventUserItem->create('createAdmin');

        $this->trigger('create');

        $this->trigger('create.event');

        return $itemId;
    }

    public function saveEventdata($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('save.pre');

        $item->save();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->save();
            }
        }
        $this->trigger('save');

        $this->trigger('save.event');

        return $item->id;
    }

    public function removeEventdata()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subItem = $item->join('Text');
        $subItem->remove();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.event');

        return true;
    
    }


}
