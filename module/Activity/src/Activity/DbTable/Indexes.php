<?php

namespace Activity\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Indexes extends TableGateway
{
    protected $tableName = 'indexes';

    protected $primaryKey = array(
        'user_id',
        'author_id',
        'message_id',
    );

    public function setParameters(Parameters $params)
    {
        if($params->user_id) {
            $this->where(array('user_id' => $params->user_id));
        }

        if($params->author_id){
            $this->where(array('author_id' => $params->author_id));
        }

        if($params->message_id){
            $this->where(array('message_id' => $params->message_id));
        }

        if ($params->rows) {
            $this->limit($params->rows);
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'message_id ASC',
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
