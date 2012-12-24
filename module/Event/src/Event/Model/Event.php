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

        $this->trigger('get.post');
        $this->trigger('get.postcache');

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

        $this->trigger('list.post');
        $this->trigger('list.postcache');

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
        
        $eventCountItem = $this->getItem('Event\Item\Count');
        $eventCountItem->event_id = $itemId;
        $eventCountItem->memberCount  = 1;
        $eventCountItem->create();

        $this->trigger('create');

        $this->trigger('create.post');

        return $itemId;
    }

    public function saveEventdata($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('save.pre');
        
        //Admin save item will remove all categories
        $categoryEventItem = $this->getItem('Event\Item\CategoryEvent');
        $categoryEventItem->event_id = $item->id;
        $categoryEventItem->remove();
        
        $item->save();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->save();
            }
        }
        $this->trigger('save');

        $this->trigger('save.post');

        return $item->id;
    }

    public function removeEventdata()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subItem = $item->join('Text');
        $subItem->remove();

        $subItem = $item->join('EventUser');
        foreach ($subItem as $eventUser) {
            $eventUser->remove();
        }

        $subItem = $item->join('EventFile');
        $subItem->remove();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    
    }


}
