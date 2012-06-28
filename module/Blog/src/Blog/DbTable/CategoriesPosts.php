<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class CategoriesPosts extends TableGateway
{
    protected $tableName ='categories_posts';
    protected $primaryKey = array('category_id', 'post_id');
}
