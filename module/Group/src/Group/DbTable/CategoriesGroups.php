<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class CategoriesGroups extends TableGateway
{
    protected $tableName ='categories_groups';
    protected $primaryKey = array('category_id', 'group_id');

    public function setParameters(Parameters $params)
    {
        if($params->group_id){
            $this->where(array('group_id' => $params->group_id));
        }

        if($params->category_id){
            $this->where(array('category_id' => $params->category_id));
        }
        
        if($params->noLimit) {
            $this->disableLimit();
        }

        return $this;
    }
}
