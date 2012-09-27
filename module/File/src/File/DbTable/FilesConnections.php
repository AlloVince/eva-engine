<?php

namespace File\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class FilesConnections extends TableGateway
{
    protected $tableName ='files_connections';

    protected $primaryKey = array('file_id', 'connect_id', 'connectType');

    public function setParameters(Parameters $params)
    {
        if($params->connect_id){
            $this->where(array('connect_id' => $params->connect_id));
        }

        if($params->file_id){
            $this->where(array('file_id' => $params->file_id));
        }

        if($params->connectType){
            $this->where(array('connectType' => $params->connectType));
        }

        return $this;
    }
}
