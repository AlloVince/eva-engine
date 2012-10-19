<?php

namespace Message\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Messages extends TableGateway
{
    protected $tableName ='messages';
    protected $primaryKey = 'id';
}
