<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Texts extends TableGateway
{
    protected $tableName ='texts';
    protected $primaryKey = 'event_id';
}
