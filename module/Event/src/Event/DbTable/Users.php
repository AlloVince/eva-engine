<?php

namespace Event\DbTable;

use Zend\Stdlib\Parameters;
use Eva\Api;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class Users extends \User\DbTable\Users
{
    public function initTableName()
    {
        $this->table = $this->getTablePrefix() . 'user_' . $this->tableName;
        return $this;
    }

    public function setParameters(Parameters $params)
    {
        $eventsUsersTable = Api::_()->getDbTable('Event\DbTable\EventsUsers');
        $eventsUsersTableName = $eventsUsersTable->initTableName()->getTable();

        if($params->inEvent){
            $this->join(
                $eventsUsersTableName,
                "id = $eventsUsersTableName.user_id"
            );

            /*
            $this->initialize();
            $select = $this->getSelect();
            $inSelect = new Select($eventsUsersTableName);
            $inSelect->columns(array('user_id'));
            $select->where->in('id', $inSelect);
            $this->setSelect($select);
             */

            if($params->eventRole){
                $this->where(array("$eventsUsersTableName.role" => $params->eventRole)); 
            }
        }

        if($params->order == 'eventcountdesc'){
            $this->columns(array(
                '*',
                'EventCount' => new Expression("count(event_id)"),
            ));
            $this->group("$eventsUsersTableName.user_id");
            $this->order('EventCount DESC');
            unset($params->order);
        }

        return parent::setParameters($params);
    }
}
