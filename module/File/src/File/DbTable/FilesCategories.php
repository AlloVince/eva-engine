<?php

namespace File\DbTable;

use Eva\Db\TableGateway\TableGateway;

class FilesCategories extends TableGateway
{
    protected $tableName ='files_categories';
    protected $primaryKey = array('file_id', 'category_id');
}
