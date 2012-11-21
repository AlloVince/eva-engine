<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class EventsFiles extends TableGateway
{
    protected $tableName = 'events_files';

    protected $primaryKey = array(
        'event_id',
        'file_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'event_id',
            'file_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->event_id){
            $this->where(array('event_id' => $params->event_id));
        }

        if($params->file_id){
            $this->where(array('file_id' => $params->file_id));
        }

        return $this;
    }
}
