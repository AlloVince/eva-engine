<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class GroupsEvents extends TableGateway
{
    protected $tableName = 'groups_events';

    protected $primaryKey = array(
        'group_id',
        'event_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'group_id',
            'event_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->group_id){
            $this->where(array('group_id' => $params->group_id));
        }

        if($params->event_id){
            $this->where(array('event_id' => $params->event_id));
        }

        return $this;
    }
}
