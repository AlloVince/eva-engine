<?php

namespace Activity\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Messages extends TableGateway
{
    protected $tableName = 'messages';

    protected $primaryKey = 'id';

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
        }

        return $this;
    }
}
