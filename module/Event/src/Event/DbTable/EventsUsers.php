<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class EventsUsers extends TableGateway
{
    protected $tableName = 'events_users';

    protected $primaryKey = array(
        'event_id',
        'user_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'event_id',
            'user_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->event_id){
            $this->where(array('event_id' => $params->event_id));
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        if($params->requestStatus){
            $this->where(array('requestStatus' => $params->requestStatus));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        $orders = array(
            'timeasc' => 'requestTime ASC',
            'timedesc' => 'requestTime DESC',
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
