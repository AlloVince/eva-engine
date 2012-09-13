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
        return $this;
    }
}
