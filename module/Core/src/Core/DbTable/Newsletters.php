<?php

namespace Core\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Newsletters extends TableGateway
{
    protected $tableName ='newsletters';
    protected $primaryKey = 'user_id';

    public function setParameters(Parameters $params)
    {
        if($params->user_id){
            $this->where(array(
                'user_id' => $params->user_id,
            ));
        }

        if($params->noLimit) {
            $this->disableLimit();
        }

        return $this;
    }
}
