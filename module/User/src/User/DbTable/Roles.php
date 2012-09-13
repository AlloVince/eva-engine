<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Roles extends TableGateway
{
    protected $tableName ='roles';
    protected $primaryKey = 'id';


    public function setParameters(Parameters $params)
    {
        return $this;
    }
}
