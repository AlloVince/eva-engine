<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class GroupsPosts extends TableGateway
{
    protected $tableName = 'groups_posts';

    protected $primaryKey = array(
        'group_id',
        'post_id',
    );
    
    protected $uniqueIndex = array(
        array(
            'group_id',
            'post_id',
        ),
    );

    public function setParameters(Parameters $params)
    {
        if($params->group_id){
            $this->where(array('group_id' => $params->group_id));
        }

        if($params->post_id){
            $this->where(array('post_id' => $params->post_id));
        }
        
        if($params->noLimit) {
            $this->disableLimit();
        }
        
        if ($params->rows) {
            $this->limit($params->rows);
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'post_id ASC',
            'iddesc' => 'post_id DESC',
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
