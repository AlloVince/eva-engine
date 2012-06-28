<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Tests extends TableGateway
{
    protected $tableName ='texts';
    protected $primaryKey = 'post_id';
}
