<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Counts extends TableGateway
{
    protected $tableName ='counts';
    protected $primaryKey = 'group_id';
}
