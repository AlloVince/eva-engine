<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class RolesUsers extends TableGateway
{
    protected $tableName ='roles_users';
    protected $primaryKey = array(
        'user_id',
        'role_id',
    );


    public function setParameters(Parameters $params)
    {
        if($params->role_id){
            $this->where(array(
                'role_id' => $params->role_id,
            ));
        }

        if($params->user_id){
            $this->where(array(
                'user_id' => $params->user_id,
            ));
        }

        return $this;
    }
}
