<?php

namespace Video\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Indexes extends TableGateway
{
    protected $tableName = 'indexes';

    protected $primaryKey = array(
        'user_id',
        'author_id',
        'message_id',
    );

    public function setParameters(Parameters $params)
    {
        return $this;
    }
}
