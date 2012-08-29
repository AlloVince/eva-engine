<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Friends extends TableGateway
{
    protected $tableName ='friends';
    protected $primaryKey = array(
        'from_user_id',
        'to_user_id'
    );

}
