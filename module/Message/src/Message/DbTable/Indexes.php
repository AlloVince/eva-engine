<?php

namespace Message\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Indexes extends TableGateway
{
    protected $tableName = 'indexes';
    protected $primaryKey = array('user_id', 'author_id', 'conversation_id');

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
        }

        if($params->author_id){
            $this->where(array('author_id' => $params->author_id));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if($params->page){
            $this->page($params->page);
        }
        
        if ($params->noLimit) {
            $this->disableLimit();
        }

        $orders = array(
            'timeasc' => 'messageTime ASC',
            'timedesc' => 'messageTime DESC',
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
