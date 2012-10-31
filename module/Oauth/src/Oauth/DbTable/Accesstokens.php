<?php

namespace Oauth\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Accesstokens extends TableGateway
{
    protected $tableName ='accesstokens';

    protected $primaryKey = array(
        'adapterKey',
        'token',
        'version',
    );

    public function setParameters(Parameters $params)
    {
        if($params->adapterKey){
            $this->where(array('adapterKey' => $params->adapterKey));
        }

        if($params->token){
            $this->where(array('token' => $params->token));
        }

        if($params->version){
            $this->where(array('version' => $params->version));
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        return $this;
    }
}
