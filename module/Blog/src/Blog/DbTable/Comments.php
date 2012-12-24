<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Comments extends TableGateway
{
    protected $tableName ='comments';
    protected $primaryKey = 'id';


    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->id){
            if(is_array($params->id)){
                $this->where(array('id' => array_unique($params->id)));
            } else {
                $this->where(array('id' => $params->id));
            }
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        if($params->post_id){
            $this->where(array('post_id' => $params->post_id));
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('title', "%$keyword%");
                return $where;
            });
        }

        if($params->status){
            $this->where(array('status' => $params->status));
        }

        if($params->visibility){
            $this->where(array('visibility' => $params->visibility));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'createTime ASC',
            'timedesc' => 'createTime DESC',
            'idarray' => 'FIELD(id, %s)',
        );
        if($params->order){
            $order = $orders[$params->order];
            if($order){
                if($params->order == 'idarray') {
                    if($params->id && is_array($params->id)){
                        $idArray = array_unique($params->id);
                        $order = sprintf($order, implode(',', array_fill(0, count($idArray), Expression::PLACEHOLDER)));
                        $this->order(array(new Expression($order, $idArray)));

                    }
                } else {
                    $this->order($order);
                }
            }
        }

        return $this;
    }
}
