<?php

namespace Video\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Atusers extends TableGateway
{
    protected $tableName = 'atusers';

    protected $primaryKey = array(
        'user_id',
        'message_id',
    );

    public function setParameters(Parameters $params)
    {
        return $this;
    }
}
