<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Friends extends TableGateway
{
    protected $tableName ='friends';

    protected $primaryKey = array(
        'from_user_id',
        'to_user_id'
    );

    public function setParameters(Parameters $params)
    {
        if($params->from_user_id){
            $this->where(array('from_user_id' => $params->from_user_id));
        }

        if($params->to_user_id){
            $this->where(array('to_user_id' => $params->to_user_id));
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }
        
        return $this;
    }
}
