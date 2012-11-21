<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class GroupsFiles extends TableGateway
{
    protected $tableName = 'groups_files';

    protected $primaryKey = array(
        'group_id',
        'file_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'group_id',
            'file_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->group_id){
            $this->where(array('group_id' => $params->group_id));
        }

        if($params->file_id){
            $this->where(array('file_id' => $params->file_id));
        }

        return $this;
    }
}
