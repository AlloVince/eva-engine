<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;

class Categories extends TableGateway
{
    protected $tableName ='categories';
    protected $primaryKey = 'id';

    public function selectCategories(\Zend\Stdlib\Parameters $params)
    {
        if($params->enableCount){
            $this->enableCount();
        }

        if($params->page){
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'createTime ASC',
            'timedesc' => 'createTime DESC',
            'titleasc' => 'categoryName ASC',
            'titledesc' => 'categoryName DESC',
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
