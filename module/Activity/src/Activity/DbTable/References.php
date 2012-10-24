<?php

namespace Activity\DbTable;

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
            $this->page($params->page);
        }

        if($params->message_id){
            $this->where(array('message_id' => $params->message_id));
        }

        if($params->reference_message_id){
            $this->where(array('reference_message_id' => $params->reference_message_id));
        }

        if($params->messageType){
            $this->where(array('messageType' => $params->messageType));
        }

        $orders = array(
            'iddesc' => 'message_id ASC',
            'iddesc' => 'message_id DESC',
        );
        if($params->order){
            $order = $orders[$params->order];
            if($order){
                $this->order($order);
            }
        }

        return $this;
    }
}
