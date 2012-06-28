<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Comments extends TableGateway
{
    protected $tableName ='comments';
    protected $primaryKey = 'id';
}
