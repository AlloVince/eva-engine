<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Tokens extends TableGateway
{
    protected $tableName ='tokens';
    protected $primaryKey = array(
        'session',
        'token',
        'userid',
    );


    public function setParameters(Parameters $params)
    {
        if($params->session){
            $this->where(array('session' => $params->session));
        }

        if($params->token){
            $this->where(array('token' => $params->token));
        }

        if($params->userid){
            $this->where(array('userid' => $params->userid));
        }

        return $this;
    }
}
