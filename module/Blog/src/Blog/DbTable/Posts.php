<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Posts extends TableGateway
{
    protected $tableName ='posts';
	protected $primaryKey = 'id';
}
