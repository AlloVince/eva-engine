<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Categories extends TableGateway
{
    protected $tableName ='categories';
    protected $primaryKey = 'id';
    
    protected $uniqueIndex = array(
        'urlName',
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
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
            'nameasc' => 'categoryName ASC',
            'namedesc' => 'categoryName DESC',
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
