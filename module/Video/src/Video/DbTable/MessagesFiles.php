<?php

namespace Video\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class MessagesFiles extends TableGateway
{
    protected $tableName = 'messages_files';

    protected $primaryKey = array(
        'message_id',
        'file_id',
    );

    public function setParameters(Parameters $params)
    {
        if($params->message_id){
            $this->where(array('message_id' => $params->message_id));
        }

        if($params->file_id){
            $this->where(array('file_id' => $params->file_id));
        }

        return $this;
    }
}
