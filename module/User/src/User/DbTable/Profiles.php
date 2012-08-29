<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Profiles extends TableGateway
{
    protected $tableName ='profiles';
    protected $primaryKey = 'user_id';

}
