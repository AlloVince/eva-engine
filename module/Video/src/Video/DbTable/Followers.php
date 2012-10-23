<?php

namespace Video\DbTable;

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
        }

        return $this;
    }
}
