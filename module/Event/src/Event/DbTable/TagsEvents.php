<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class TagsEvents extends TableGateway
{
    protected $tableName ='tags_events';
    protected $primaryKey = array('tag_id', 'event_id');

    public function setParameters(Parameters $params)
    {
        if($params->event_id){
            $this->where(array('event_id' => $params->event_id));
        }

        if($params->tag_id){
            $this->where(array('tag_id' => $params->tag_id));
        }
        
        if($params->noLimit) {
            $this->disableLimit();
        }

        return $this;
    }
}
