<?php

namespace Group\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Tag extends AbstractModel
{
    public function createTag($data = null)
    {
        return $this->createItem($data);
    }

    public function saveTag($data = null)
    {
        return $this->saveItem($data);
    }

    public function removeTag($data = null)
    {
        return $this->removeItem($data);
    }

    public function getTag($tagIdOrName, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($tagIdOrName)){
            $this->setItem(array(
                'id' => $tagIdOrName,
            ));
        } else {
            $this->setItem(array(
                'tagName' => $tagIdOrName,
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

    public function getTagList(array $map = array())
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
