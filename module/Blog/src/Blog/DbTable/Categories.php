<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Categories extends TableGateway
{
    protected $tableName ='categories';
    protected $primaryKey = 'id';
}
