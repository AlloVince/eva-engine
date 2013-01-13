<?php

namespace Group\Model;

use Event\Model\Event as EventModel;

class Event extends EventModel
{
    protected $groupEventPaginator;
    protected $itemClass = 'Group\Item\Event';

    public function getGroupEventPaginator()
    {
        return $this->groupEventPaginator;
    }

    public function getGroupEventList($params)
    {
        $indexItem = $this->getItem('Group\Item\GroupEvent');

        $defaultParams = array(
            'group_id' => '',
            'order' => 'iddesc',
            'page' => 1,
            'rows' => 20,
        );

        $itemQueryParams = array_merge($defaultParams, $params);

        $indexItem->collections($itemQueryParams);
        $this->groupEventPaginator = $indexItem->getPaginator();

        $eventIdArray = array();
        foreach($indexItem as $index){
            $eventIdArray[] = $index['event_id'];
        }
        if(!$eventIdArray){
            $this->setItemList(array(
                'noResult' => true
            ));
        } else {
            $this->setItemList(array(
                'id' => $eventIdArray,
                'order' => 'idarray',
                'noLimit' => true,
            ));
        }
        return $this;
    }
}
