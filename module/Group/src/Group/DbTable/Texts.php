<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Texts extends TableGateway
{
    protected $tableName ='texts';
    protected $primaryKey = 'group_id';
}
