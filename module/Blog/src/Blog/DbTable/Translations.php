<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Translations extends TableGateway
{
    protected $tableName ='translations';
    protected $primaryKey = array('post_id', 'language');
}
