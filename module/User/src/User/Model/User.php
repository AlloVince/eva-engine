<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class User extends AbstractModel
{
    protected $map = array(
        'small' => array(
        
        ),
        'medium' => array(
        
        ),
        'large' => array(
        
        )
    );

    public function createUser(array $data = array())
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

    public function saveUser(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('save.pre');

        //Admin save item will remove all user roles
        $roleUserItem = $this->getItem('User\Item\RoleUser');
        $roleUserItem->user_id = $item->id;
        $roleUserItem->remove();
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

    public function removeUser(array $map = array())
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subItem = $item->join('Profile');
        $subItem->remove();

        $subItem = $item->join('Account');
        $subItem->remove();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    }

    public function getUser($userIdOrName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($userIdOrName)){
            $this->setItem(array(
                'id' => $userIdOrName,
            ));
        } elseif(is_string($userIdOrName)) {
            $user = $this->getItem()->getDataClass()->columns(array('id'))->where(array(
                'userName' => $userIdOrName
            ))->find('one');
            if(!$user){
                return array();
            }
            $this->setItem(array(
                'id' => $user['id'],
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

    public function getUserList(array $itemListParameters = array(), $map = null)
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
