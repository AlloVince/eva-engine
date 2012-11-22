<?php

namespace Event\Item;

use Eva\Mvc\Item\AbstractItem;

class EventFile extends AbstractItem
{
    protected $dataSourceClass = 'Event\DbTable\EventsFiles';

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
            'event_id' => $eventId
        ))->remove();
        $data = $this->toArray();
        $saveData['event_id'] = $eventId;
        $saveData['file_id'] = $data['file_id'];
        $dataClass->create($saveData);
    }
}
