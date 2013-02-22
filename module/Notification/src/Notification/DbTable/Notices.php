<?php

namespace Notification\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Notices extends TableGateway
{
    protected $tableName = 'notices';
    protected $primaryKey = array('user_id', 'message_id');

    public function setParameters(Parameters $params)
    {
        if($params->order == 'read'){
            $this->order('readFlag ASC');
            $this->order('createTime DESC');
        } 
        
        if($params->status){
            $this->where(array('status' => $params->status));
        }
        
        if($params->readFlag === 0 || $params->readFlag === 1){
            $this->where(array('readFlag' => $params->readFlag));
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        parent::setParameters($params);
        return $this;
    }
}
