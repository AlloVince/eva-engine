<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;
use Eva\Api;

class Groups extends TableGateway
{
    protected $tableName ='groups';

    protected $primaryKey = 'id';

    protected $uniqueIndex = array(
        'groupKey',
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('groupName', "%$keyword%");
                return $where;
            });
        }

        if($params->status){
            $this->where(array('status' => $params->status));
        }
        
        if($params->memberEnable){
            $this->where(array('memberEnable' => $params->memberEnable));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if ($params->category) {
            $categoryModel = Api::_()->getModel('Group\Model\Category');
            $categoryItem = $categoryModel->getCategory($params->category);

            if ($categoryItem->id) {
                $categoryGroupDb = Api::_()->getDbTable('Group\DbTable\CategoriesGroups');
                $categoryGroupTabName = $categoryGroupDb->initTableName()->table;
                $this->join(
                    $categoryGroupTabName,
                    "{$this->table}.id = $categoryGroupTabName.group_id",
                    array('*'),
                    'inner'
                );
                $this->where(array("$categoryGroupTabName.category_id" => $categoryItem->id));
            } else {
                $this->where(array("id" => 0));
            }    
        }

        if ($params->order == 'memberdesc' || $params->order == 'memberasc') {
            $groupCountDb = Api::_()->getDbTable('Group\DbTable\Counts');
            $groupCountTabName = $groupCountDb->initTableName()->table;
            $this->join(
                $groupCountTabName,
                "{$this->table}.id = $groupCountTabName.group_id",
                array('*'),
                'inner'
            );
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'createTime ASC',
            'timedesc' => 'createTime DESC',
            'titleasc' => 'groupName ASC',
            'titledesc' => 'groupName DESC',
            'memberdesc' => 'memberCount DESC',
            'memberasc' => 'memberCount ASC',
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
