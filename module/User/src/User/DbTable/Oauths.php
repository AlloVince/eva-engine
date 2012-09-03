<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Oauths extends TableGateway
{
    protected $tableName ='oauths';
    protected $primaryKey = array(
        'user_id',
        'appType',
    );

}
