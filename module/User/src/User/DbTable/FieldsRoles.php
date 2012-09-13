<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class FieldsRoles extends TableGateway
{
    protected $tableName ='fields_roles';
    protected $primaryKey = array('field_id', 'role_id');

    public function setParameters(Parameters $params)
    {
        if($params->field_id){
            $this->where(array(
                'field_id' => $params->field_id,
            ));
        }

        if($params->role_id){
            $this->where(array(
                'role_id' => $params->role_id,
            ));
        }
        return $this;
    }
}
