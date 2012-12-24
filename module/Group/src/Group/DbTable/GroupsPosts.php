<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class GroupsEvents extends TableGateway
{
    protected $tableName = 'groups_posts';

    protected $primaryKey = array(
        'group_id',
        'post_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'group_id',
            'post_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->group_id){
            $this->where(array('group_id' => $params->group_id));
        }

        if($params->post_id){
            $this->where(array('post_id' => $params->post_id));
        }

        return $this;
    }
}
