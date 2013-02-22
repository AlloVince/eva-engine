<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Tokens extends TableGateway
{
    protected $tableName ='tokens';
    protected $primaryKey = array(
        'sessionId',
        'token',
        'userHash',
    );


    public function setParameters(Parameters $params)
    {
        if($params->session){
            $this->where(array('session' => $params->session));
        }

        if($params->token){
            $this->where(array('token' => $params->token));
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        return $this;
    }
}
