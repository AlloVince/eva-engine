<?php

namespace Group\DbTable;

use Zend\Stdlib\Parameters;
use Eva\Api;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class Events extends \Event\DbTable\Events
{
    public function initTableName()
    {
        $this->table = $this->getTablePrefix() . 'event_' . $this->tableName;
        return $this;
    }

    public function setParameters(Parameters $params)
    {
        $groupsEventsTable = Api::_()->getDbTable('Group\DbTable\GroupsEvents');
        $groupsEventsTableName = $groupsEventsTable->initTableName()->getTable();

        if($params->group_id){
            $params->inGroup = true;
        }

        if($params->inGroup){
            $groupId = $params->group_id;

            $this->where(function($where) use ($groupsEventsTableName, $groupId){
                $select = new Select($groupsEventsTableName);
                $select->columns(array('event_id'));
                if($groupId){
                    $select->where(array(
                        'group_id' => $groupId
                    ));
                }
                $where->in('id', $select);
                return $where;
            });
        }
        return parent::setParameters($params);
    }
}
