<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Fieldvalues extends TableGateway
{
    protected $tableName ='fieldvalues';
    protected $primaryKey = array('user_id', 'field_id');

    public function setParameters(Parameters $params)
    {
        if($params->user_id){
            $this->where(array(
                'user_id' => $params->user_id,
            ));
        }
        return $this;
    }
}
