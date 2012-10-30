<?php

namespace Activity\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Followers extends TableGateway
{
    protected $tableName ='followers';

    protected $primaryKey = array(
        'user_id',
        'follower_id',
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        if($params->follower_id){
            $this->where(array('follower_id' => $params->follower_id));
        }

        return $this;
    }
}
