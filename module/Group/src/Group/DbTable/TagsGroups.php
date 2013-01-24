<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class TagsGroups extends TableGateway
{
    protected $tableName ='tags_groups';
    protected $primaryKey = array('tag_id', 'group_id');

    public function setParameters(Parameters $params)
    {
        if($params->group_id){
            $this->where(array('group_id' => $params->group_id));
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
