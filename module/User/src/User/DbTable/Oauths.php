<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Oauths extends TableGateway
{
    protected $tableName ='oauths';
    protected $primaryKey = array(
        'user_id',
        'appType',
    );

    public function setParameters(Parameters $params)
    {
        if(false === $params->limit){
            $this->disableLimit();
        }

        if($params->columns){
            $this->columns($params->columns);
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        return $this;
    }

}
