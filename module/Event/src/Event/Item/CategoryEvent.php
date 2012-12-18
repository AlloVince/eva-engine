<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class CategoryEvent extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\CategoriesEvents';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create($mapKey = 'create')
    {
        $eventItem = $this->getModel()->getItem('Event\Item\Event');
        $eventId = $eventItem->id;
        if(!$eventId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(count($this) > 0){
            foreach($this as $item){
                $item['event_id'] = $eventId;
                $dataClass->create($item);
            }
        }
    }

    public function save($mapKey = 'save')
    {
        $eventItem = $this->getModel()->getItem('Event\Item\Event');
        $eventId = $eventItem->id;
        
        if(!$eventId) {
            return;
        }
        $dataClass = $this->getDataClass();
        if(count($this) > 0){
            foreach($this as $item){
                $item['event_id'] = $eventId;
                $dataClass->where(array(
                    'event_id' => $eventId,
                    'category_id' => $item['category_id'],
                ))->remove();
                $dataClass->create($item);
            }
        }
    }
}
