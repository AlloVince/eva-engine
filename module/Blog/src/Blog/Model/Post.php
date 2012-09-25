<?php

namespace Blog\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModelService;

class Post extends AbstractModelService
{
    protected $itemTableName = 'Blog\DbTable\Posts';


    public function createPost($data = null)
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

    public function savePost($data = null)
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

    public function removePost()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subItem = $item->join('Text');
        $subItem->remove();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    
    }

    public function getPost($postIdOrUrlName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($postIdOrUrlName)){
            $this->setItem(array(
                'id' => $postIdOrUrlName,
            ));
        } elseif(is_string($postIdOrUrlName)) {
            $this->setItem(array(
                'urlName' => $postIdOrUrlName,
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

    public function getPostList(array $map = array())
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
