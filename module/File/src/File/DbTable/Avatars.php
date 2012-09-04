<?php

namespace File\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Avatars extends TableGateway
{
    protected $tableName ='avatars';
    protected $primaryKey = array('file_id', 'users_id');
}
