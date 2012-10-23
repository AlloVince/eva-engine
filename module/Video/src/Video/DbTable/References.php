<?php

namespace Video\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class References extends TableGateway
{
    protected $tableName = 'references';

    protected $primaryKey = array(
        'reference_user_id',
        'reference_message_id',
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
        }

        return $this;
    }
}
