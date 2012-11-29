<?php

namespace Group\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Group extends AbstractModel
{
    protected $itemTableName = 'Group\DbTable\Groups';

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


    public function getGroup($groupIdOrUrlName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($groupIdOrUrlName)){
            $this->setItem(array(
                'id' => $groupIdOrUrlName,
            ));
        } elseif(is_string($groupIdOrUrlName)) {
            $this->setItem(array(
                'urlName' => $groupIdOrUrlName,
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

        $this->trigger('get.group');
        $this->trigger('get.groupcache');

        return $item;
    }

    public function getGroupList(array $map = array())
    {
        $this->trigger('list.precache');

        $this->trigger('list.pre');

        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        $this->trigger('get');

        $this->trigger('list.group');
        $this->trigger('list.groupcache');

        return $item;
    }

    public function createGroup($data = null)
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
        
        $groupUserItem = $this->getItem('Group\Item\GroupUser');
        $groupUserItem->group_id = $itemId;
        $groupUserItem->user_id  = $item->user_id;
        $groupUserItem->create('createAdmin');

        $this->trigger('create');

        $this->trigger('create.group');

        return $itemId;
    }

    public function saveGroup($data = null)
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

        $this->trigger('save.group');

        return $item->id;
    }

    public function removeGroup()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subItem = $item->join('Text');
        $subItem->remove();
        
        $subItem = $item->join('GroupUser');
        $subItem->remove();
        
        $subItem = $item->join('GroupFile');
        $subItem->remove();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.group');

        return true;
    
    }


}
