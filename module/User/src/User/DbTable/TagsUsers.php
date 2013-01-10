<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class TagsUsers extends TableGateway
{
    protected $tableName ='tags_users';
    protected $primaryKey = array('tag_id', 'user_id');

    public function setParameters(Parameters $params)
    {
        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
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
