<?php

namespace File\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Files extends TableGateway
{
    protected $tableName ='files';
    protected $primaryKey = 'id';
}
