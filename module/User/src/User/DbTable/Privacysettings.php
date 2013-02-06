<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Privacysettings extends TableGateway
{
    protected $tableName ='privacysettings';
    protected $primaryKey = 'user_id';


    public function setParameters(Parameters $params)
    {
        parent::setParameters($params);

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        return $this;
    }
}
