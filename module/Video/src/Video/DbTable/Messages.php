<?php

namespace Video\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Messages extends TableGateway
{
    protected $tableName = 'messages';

    protected $primaryKey = 'id';

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('content', "%$keyword%");
                return $where;
            });
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
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
