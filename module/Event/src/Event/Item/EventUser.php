<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class EventUser extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\EventsUsers';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create()
    {
        $eventItem = $this->getModel()->getItem();
        $eventId = $eventItem->id;
        if(!$eventId || !$this->file_id) {
            return;
        }

        $data = $this->toArray();
        $data['event_id'] = $eventId;
        $dataClass = $this->getDataClass();
        $dataClass->create($data);
    }

    public function save()
    {
        $eventItem = $this->getModel()->getItem();
        $eventId = $eventItem->id;
        if(!$eventId) {
            return;
        }

        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'event_id' => $fieldId
        ))->remove();
        if(isset($this[0])){
            foreach($this as $item){
                $item['event_id'] = $eventId;
                $dataClass->create($item);
            }
        }
    }
}
