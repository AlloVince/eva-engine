<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Users extends TableGateway
{
    protected $tableName ='users';
    protected $primaryKey = 'id';


    public function setParameters(Parameters $params)
    {
        $this->enableCount();

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('userName', "%$keyword%");
                return $where;
            });
        }

        if($params->status){
            $this->where(array('status' => $params->status));
        }

        if($params->gender){
            $this->where(array('gender' => $params->gender));
        }

        if($params->onlineStatus){
            $this->where(array('onlineStatus' => $params->onlineStatus));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if($params->page){
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'updateTime ASC',
            'timedesc' => 'updateTime DESC',
            'titleasc' => 'title ASC',
            'titledesc' => 'title DESC',
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
