<?php

namespace Album\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Counts extends TableGateway
{
    protected $tableName ='counts';
    protected $primaryKey = 'album_id';
}
