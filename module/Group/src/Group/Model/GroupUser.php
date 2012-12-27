<?php

namespace Group\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class GroupUser extends AbstractModel
{
    protected $itemTableName = 'Group\DbTable\GroupsUsers';
    
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

    public function getGroupUserList(array $itemListParameters = array(), $map = null)
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

    public function joinGroup($data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $userId = $item->user_id;
        $groupId = $item->group_id;

        $oldItem = clone $item;
        $oldItem->self(array('*'));

        if(!$oldItem->requestStatus){
            $item->create();
        } else {
            $item->save();
        }

        $countItem = $this->getItem('Group\Item\Count');
        $countItem->group_id = $group;
        $countItem->self(array('*'));
        $countItem->memberCount += 1;
        $countItem->save();

        $this->trigger('create');
        $this->trigger('create.post');

        return true;
    }


    public function unjoinGroup()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $userId = $item->user_id;
        $groupId = $item->group_id;

        $item->remove();
        
        $countItem = $this->getItem('Group\Item\Count');
        $countItem->group_id = $group;
        $countItem->self(array('*'));
        $countItem->memberCount -= 1;
        $countItem->save();

        $this->trigger('remove');
        $this->trigger('remove.post');

        return true;
    }
}
