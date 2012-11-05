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
        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('userName', "%$keyword%");
                return $where;
            });
        }

        if($params->id){
            if(is_array($params->id)){
                $params->id = array_unique($params->id);
            }
            $this->where(array('id' => $params->id));
        }

        if($params->columns) {
            $this->columns($params->columns);
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
        
        if($params->emails){
            $emails = $params->emails;
            $this->where(function($where) use ($emails){
                $where->in('email', $emails);
                return $where;
            });
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'registerTime ASC',
            'timedesc' => 'registerTime DESC',
            'nameasc' => 'userName ASC',
            'namedesc' => 'userName DESC',
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
