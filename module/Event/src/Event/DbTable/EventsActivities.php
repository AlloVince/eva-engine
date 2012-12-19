<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class EventsActivities extends TableGateway
{
    protected $tableName = 'events_activities';

    protected $primaryKey = array(
        'event_id',
        'message_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'event_id',
            'message_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->event_id){
            $this->where(array('event_id' => $params->event_id));
        }

        if($params->file_id){
            $this->where(array('message_id' => $params->message_id));
        }

        if ($params->rows) {
            $this->limit($params->rows);
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'message_id ASC',
            'iddesc' => 'message_id DESC',
        );
        if($params->order){
            $order = $orders[$params->order];
            if($order){
                $this->order($order);
            }
        }

        return $this;
    }
}
