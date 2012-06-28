<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class TagsPosts extends TableGateway
{
    protected $tableName ='tags_posts';
    protected $primaryKey = array('tag_id', 'post_id');
}
