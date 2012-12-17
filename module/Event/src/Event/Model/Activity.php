<?php

namespace Event\Model;

use Activity\Model\Activity as ActivityModel;

class Activity extends ActivityModel
{
    protected $eventActivityPaginator;

    public function getEventActivityPaginator()
    {
        return $this->eventActivityPaginator;
    }

    public function getEventActivityList($params)
    {
        $indexItem = $this->getItem('Event\Item\EventActivity');

        $defaultParams = array(
            'event_id' => '',
            'order' => 'iddesc',
            'page' => 1,
            'rows' => 20,
        );

        $itemQueryParams = array_merge($defaultParams, $params);

        $indexItem->collections($itemQueryParams);
        $this->eventActivityPaginator = $indexItem->getPaginator();

        $messageIdArray = array();
        foreach($indexItem as $index){
            $messageIdArray[] = $index['message_id'];
        }
        if(!$messageIdArray){
            $this->setItemList(array(
                'noResult' => true
            ));
        } else {
            $this->setItemList(array(
                'id' => $messageIdArray,
                'order' => 'idarray',
                'noLimit' => true,
            ));
        }
        return $this;
    }
}
