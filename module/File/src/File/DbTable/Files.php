<?php

namespace File\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Files extends TableGateway
{
    protected $tableName ='files';
    protected $primaryKey = 'id';


    public function selectFiles(\Zend\Stdlib\Parameters $params)
    {
        if($params->enableCount){
            $this->enableCount();
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('title', "%$keyword%");
                return $where;
            });
        }

        if($params->status){
            $this->where(array('fileExtension' => $params->extension));
        }

        if($params->page){
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'createTime ASC',
            'timedesc' => 'createTime DESC',
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
