<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Tag extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Tags';

    protected $map = array(
    );

    public function create($mapKey = 'create')
    {
        if(isset($this[0])){
            return $this->createSaveTagWithPost();
        }
        return parent::create($mapKey);
    }

    public function save($mapKey = 'save')
    {
        if(isset($this[0])){
            return $this->createSaveTagWithPost();
        }
        return parent::save($mapKey);
    }

    public function getParentId()
    {
        if(!$this->parentId){
            $this->parentId = 0;
        }
    }

    protected function createSaveTagWithPost()
    {
        $tagIdArray = array();
        $dataClass = $this->getDataClass();
        foreach($this as $item){
            $tagName = $item['tagName'];
            if(!$tagName){
                continue;
            }
            
            $tag = $dataClass->where(array(
                'tagName' => $tagName
            ))->find('one');

            $item['parentId'] = $item['parentId'] ? $item['parentId'] :0;
            $item['orderNumber'] = $item['orderNumber'] ? $item['orderNumber'] :0;

            if($tag){
                $tagId = $tag['id'];
                $item['id'] = $tagId;
                $dataClass->where(array('id' => $tagId))->save($item);
            } else {
                $dataClass->create($item);
                $tagId = $dataClass->getLastInsertValue();
            }

            if($tagId){
                $tagIdArray[] = $tagId;
            }
        }

        $postItem = $this->getModel()->getItem('Blog\Item\Post');
        $postId = $postItem->id;
        if(!$tagIdArray || !$postId) {
            return $tagIdArray;
        }

        $tagPostItem = $this->getModel()->getItem('Blog\Item\TagPost');
        $tagPostItem->getDataClass()->where(array(
            'post_id' => $postId,
        ))->remove();
        foreach($tagIdArray as $tagId){
            $tagPostItem->clear();
            $tagPostItem->post_id = $postId;
            $tagPostItem->tag_id = $tagId;
            $tagPostItem->create();
        }
        return $tagIdArray;
    }
}
