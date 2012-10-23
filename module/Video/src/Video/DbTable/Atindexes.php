<?php

namespace Video\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Atindexes extends TableGateway
{
    protected $tableName = 'atindexes';

    protected $primaryKey = array(
        'user_id',
        'message_id',
    );

    public function setParameters(Parameters $params)
    {
        return $this;
    }
}
