<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class EventsAlbums extends TableGateway
{
    protected $tableName = 'events_albums';

    protected $primaryKey = array(
        'event_id',
        'album_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'event_id',
            'album_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->event_id){
            $this->where(array('event_id' => $params->event_id));
        }

        if($params->album_id){
            $this->where(array('album_id' => $params->album_id));
        }

        if ($params->rows) {
            $this->limit($params->rows);
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'album_id ASC',
            'iddesc' => 'album_id DESC',
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
