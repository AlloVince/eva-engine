<?php

namespace File\DbTable;

use Eva\Db\TableGateway\TableGateway;

class FilesConnections extends TableGateway
{
    protected $tableName ='files_connections';
    protected $primaryKey = array('file_id', 'connect_id');
}
