<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class Tag extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\Tags';

    protected $map = array(
        'create' => array(
            'getParentId()',
        ),
        'save' => array(
        )
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

        $groupItem = $this->getModel()->getItem('Group\Item\Group');
        $groupId = $groupItem->id;
        if(!$tagIdArray || !$groupId) {
            return $tagIdArray;
        }

        $tagGroupItem = $this->getModel()->getItem('Group\Item\TagGroup');
        $tagGroupItem->getDataClass()->where(array(
            'group_id' => $groupId,
        ))->remove();
        foreach($tagIdArray as $tagId){
            $tagGroupItem->clear();
            $tagGroupItem->group_id = $groupId;
            $tagGroupItem->tag_id = $tagId;
            $tagGroupItem->create();
        }
        return $tagIdArray;
    }
}
