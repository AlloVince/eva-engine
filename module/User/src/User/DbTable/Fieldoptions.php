<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Fieldoptions extends TableGateway
{
    protected $tableName ='fieldoptions';
    protected $primaryKey = 'id';


    public function setParameters(Parameters $params)
    {
        return $this;
    }
}
