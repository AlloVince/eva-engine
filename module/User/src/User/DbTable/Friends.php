<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Friends extends TableGateway
{
    protected $tableName ='friends';

    protected $primaryKey = array(
        'user_id',
        'friend_id'
    );

    public function setParameters(Parameters $params)
    {
        if($params->user_id){
            if(is_array($params->user_id)){
                $this->where(array('user_id' => array_unique($params->user_id)));
            } else {
                $this->where(array('user_id' => $params->user_id));
            }
        }

        if($params->friend_id){
            if(is_array($params->friend_id)){
                $this->where(array('friend_id' => array_unique($params->friend_id)));
            } else {
                $this->where(array('friend_id' => $params->friend_id));
            }
        }

        if($params->relationshipStatus){
            $this->where(array('relationshipStatus' => $params->relationshipStatus));
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        return $this;
    }
}
