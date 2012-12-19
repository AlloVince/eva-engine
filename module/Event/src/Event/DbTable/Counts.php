<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Counts extends TableGateway
{
    protected $tableName ='counts';
    protected $primaryKey = 'event_id';
}
