<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Tag extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Tags';

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

        $userItem = $this->getModel()->getItem('User\Item\User');
        $userId = $userItem->id;
        if(!$tagIdArray || !$userId) {
            return $tagIdArray;
        }

        $tagUserItem = $this->getModel()->getItem('User\Item\TagUser');
        $tagUserItem->getDataClass()->where(array(
            'user_id' => $userId,
        ))->remove();
        foreach($tagIdArray as $tagId){
            $tagUserItem->clear();
            $tagUserItem->user_id = $userId;
            $tagUserItem->tag_id = $tagId;
            $tagUserItem->create();
        }
        return $tagIdArray;
    }
}
