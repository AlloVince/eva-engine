<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Codes extends TableGateway
{
    protected $tableName ='codes';
    protected $primaryKey = 'id';
    protected $uniqueIndex = array(
        'code'
    );


    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->code){
            $this->where(array('code' => $params->code));
        }

        if($params->codeType){
            $this->where(array('codeType' => $params->codeType));
        }

        if($params->codeStatus){
            $this->where(array('codeStatus' => $params->codeStatus));
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_i));
        }
        
        return $this;
    }
}
