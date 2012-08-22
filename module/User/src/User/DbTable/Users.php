<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Users extends TableGateway
{
    protected $tableName ='users';
    protected $primaryKey = 'id';

}
