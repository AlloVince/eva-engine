<?php

namespace Payment\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Logs extends TableGateway
{
    protected $tableName ='logs';
    protected $primaryKey = 'id';
}
