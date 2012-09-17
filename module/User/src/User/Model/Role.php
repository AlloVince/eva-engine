<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModelService;

class Role extends AbstractModelService
{

    public function createRole(array $data = array())
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

    public function saveRole(array $data = array())
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

    
        $this->trigger('save.post');


        return $item->id;

    }

    public function removeRole(array $map = array())
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();
        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    }

    public function getRole($roleIdOrName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($roleIdOrName)){
            $this->setItem(array(
                'id' => $roleIdOrName,
            ));
        } elseif(is_string($roleIdOrName)) {
            $this->setItem(array(
                'roleName' => $roleIdOrName,
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

    public function getRoleList(array $itemListParameters = array(), $map = null)
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
