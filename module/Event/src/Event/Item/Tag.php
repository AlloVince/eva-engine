<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class Tag extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\Tags';

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

        $eventItem = $this->getModel()->getItem('Event\Item\Event');
        $eventId = $eventItem->id;
        if(!$tagIdArray || !$eventId) {
            return $tagIdArray;
        }

        $tagEventItem = $this->getModel()->getItem('Event\Item\TagEvent');
        $tagEventItem->getDataClass()->where(array(
            'event_id' => $eventId,
        ))->remove();
        foreach($tagIdArray as $tagId){
            $tagEventItem->clear();
            $tagEventItem->event_id = $eventId;
            $tagEventItem->tag_id = $tagId;
            $tagEventItem->create();
        }
        return $tagIdArray;
    }
}
