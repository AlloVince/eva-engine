<?php

namespace Event\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel,
    Eva\Date\Calendar as CalendarLib;

class Calendar extends AbstractModel
{
    protected $evnetList;

    public function getEventList()
    {
        return $this->eventList;
    }

    public function getEventCalendar(array $params = array(), $items = array())
    {
        $calendar = new CalendarLib($params);
        $calendarArray = $calendar->toArray(); 
        
        $query = array(
            'afterStartDay' => $calendarArray['beginDay'],
            'beforeStartDay' => $calendarArray['finishDay'],
            'noLimit' => true,
        );
        
        if (!$items) {
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $this->eventList = $items = $itemModel->setItemList($query)->getEventdataList();
            $items = $items ? $items->toArray() : array();
        }

        if (count($items) == 0) {
            return $calendarArray;
        }
        
        foreach($calendarArray['days'] as $weekKey=>$weekArray){
            foreach($weekArray as $dayKey=>$day){
                foreach ($items as $key=>$event) {
                    if($day['datedb'] == $event['startDay']){
                        $calendarArray['days'][$weekKey][$dayKey]['Events'][] = $event;
                        unset($items[$key]);
                    }
                }
            }
        }

        return $calendarArray;
    }
}
