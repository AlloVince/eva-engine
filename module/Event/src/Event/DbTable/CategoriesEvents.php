<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class CategoriesEvents extends TableGateway
{
    protected $tableName ='categories_events';
    protected $primaryKey = array('category_id', 'event_id');

    public function setParameters(Parameters $params)
    {
        if($params->event_id){
            $this->where(array('event_id' => $params->event_id));
        }

        if($params->category_id){
            $this->where(array('category_id' => $params->category_id));
        }

        return $this;
    }
}
