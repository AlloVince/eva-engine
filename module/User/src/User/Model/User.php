<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModelService;

class User extends AbstractModelService
{
    public function createUser()
    {
        $item = $this->getItem();
        
        $this->trigger('create.pre');

        $itemId = $item->create();

        if($item->hasRelationships()){
            foreach($item->getRelationships() as $key => $relItem){
                $relItem->create();
            }
        }
        $this->trigger('create');

    
        $this->trigger('create.post');


        return $itemId;
    }

    public function saveUser()
    {
    }

    public function removeUser()
    {
    
    }

    public function getUser($userIdOrName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($userIdOrName)){
            $this->setItem(array(
                'id' => $userIdOrName,
            ));
        } elseif(is_string($userIdOrName)) {
            $this->setItem(array(
                'userName' => $userIdOrName,
            ));
        }

        $this->trigger('get.pre');

        $item = $this->getItem()->self();

        if($map){
            $item = $item->toArray($map);
        }

        $this->trigger('get');

        $this->trigger('get.post');
        $this->trigger('get.postcache');

        return $item;
    }

    public function getUserList()
    {
    }
}
