<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class TagsPosts extends TableGateway
{
    protected $tableName ='tags_posts';
    protected $primaryKey = array('tag_id', 'post_id');

    public function setParameters(Parameters $params)
    {
        if($params->post_id){
            $this->where(array('post_id' => $params->post_id));
        }

        if($params->tag_id){
            $this->where(array('tag_id' => $params->tag_id));
        }
        
        if($params->noLimit) {
            $this->disableLimit();
        }

        return $this;
    }
}
