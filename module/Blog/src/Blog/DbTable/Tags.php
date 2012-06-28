<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Tags extends TableGateway
{
    protected $tableName ='tags';
    protected $primaryKey = 'id';
}
