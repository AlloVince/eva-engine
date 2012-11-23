<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Roles extends TableGateway
{
    protected $tableName ='roles';
    protected $primaryKey = 'id';
    protected $uniqueIndex = array(
        'roleKey',
    );


    public function setParameters(Parameters $params)
    {
        if($params->noLimit) {
            $this->disableLimit();
        }

        return $this;
    }
}
