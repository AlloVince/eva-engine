<?php

namespace Group\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;
use Zend\Db\Sql\Expression;
use Eva\Api;

class Tags extends TableGateway
{
    protected $tableName ='tags';
    protected $primaryKey = 'id';
    
    protected $uniqueIndex = array(
        'tagName',
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->noLimit) {
            $this->disableLimit();
        }

        if ($params->order == 'groupcountdesc' || $params->order == 'groupcountasc') {
            $groupTagTable = Api::_()->getDbTable('Group\DbTable\TagsGroups');
            $groupTagTableName = $groupTagTable->initTableName()->getTable();

            $this->join(
                $groupTagTableName,
                "id = $groupTagTableName.tag_id"
            );
            $this->columns(array(
                '*',
                'GroupCount' => new Expression("count(group_id)"),
            ));
            $this->group('tag_id');
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'nameasc' => 'tagName ASC',
            'namedesc' => 'tagName DESC',
            'groupcountasc' => 'GroupCount ASC',
            'groupcountdesc' => 'GroupCount DESC',
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
