<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Accounts extends TableGateway
{
    protected $tableName ='accounts';
    protected $primaryKey = 'user_id';

}
