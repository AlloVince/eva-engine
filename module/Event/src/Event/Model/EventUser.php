<?php

namespace Event\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class EventUser extends AbstractModel
{
    protected $itemTableName = 'Event\DbTable\EventsUsers';
    
    protected $userList;

    public function getUserList()
    {
        return $this->userList;
    }

    public function setUserList($userList)
    {
        $this->userList = $userList;
        return $this;
    }

    public function getEventUserList(array $itemListParameters = array(), $map = null)
    {
        $this->trigger('list.precache');

        $userList = $this->getUserList();
        if($userList){
            $userIdArray = array();
            foreach($userList as $user) {
                $userIdArray[] = $user['id'];
            }
            if($userIdArray){
                $this->itemListParameters['user_id'] = $userIdArray;
            }
        }

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

    public function joinEvent($data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $userId = $item->user_id;
        $eventId = $item->event_id;

        $oldItem = clone $item;
        $oldItem->self(array('*'));

        if(!$oldItem->requestStatus){
            $item->create();
        } else {
            $item->save();
        }

        $this->trigger('create');
        $this->trigger('create.post');

        return true;
    }


    public function unjoinEvent()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $userId = $item->user_id;
        $eventId = $item->event_id;

        $item->remove();

        $this->trigger('remove');
        $this->trigger('remove.post');

        return true;
    }
}
