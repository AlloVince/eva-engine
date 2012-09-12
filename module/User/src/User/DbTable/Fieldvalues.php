<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Fieldvalues extends TableGateway
{
    protected $tableName ='fieldvalues';
    protected $primaryKey = 'field_id';

    public function setParameters(Parameters $params)
    {
        return $this;
    }
}
