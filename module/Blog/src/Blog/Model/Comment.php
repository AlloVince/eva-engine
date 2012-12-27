<?php

namespace Blog\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Comment extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Comments';

    public function getComment($commentId = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($commentId)){
            $this->setItem(array(
                'id' => $commentId,
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

    public function getCommentList(array $map = array())
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

    public function createComment($data = null)
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

    public function saveComment($data = null)
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

    public function removeComment()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    
    }

    public function updatePostCommentCount()
    {
        $item = $this->getItem();
        if(!$item->post_id){
            return false;
        }
        $postId = $item->post_id;
        $postDbTable = $this->getItem('Blog\Item\Post')->getDataClass();
        $postDbTable->where(array(
            'id' => $postId,
        ))->save(array(
            'commentCount' => $item->getDataClass()->where(array(
                'post_id' => $postId
            ))->find('count')
        ));
    }

}
