<?php

namespace Core\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Newsletter extends AbstractModel
{
    public function getNewsletter($userId = null, array $map = array())
    {
        $this->trigger('get.precache');

        $this->setItem(array(
            'user_id' => $userId,
        ));
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

    public function createNewsletter(array $data = array())
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

    public function saveNewsletter(array $data = array())
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

    public function removeNewsletter(array $map = array())
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();
        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    }

    public function getNewsletterList(array $itemListParameters = array(), $map = null)
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
