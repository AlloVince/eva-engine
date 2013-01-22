<?php

namespace Event\DbTable;

use Zend\Stdlib\Parameters;
use Eva\Api;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class Albums extends \Album\DbTable\Albums
{
    public function initTableName()
    {
        $this->table = $this->getTablePrefix() . 'album_' . $this->tableName;
        return $this;
    }

    public function setParameters(Parameters $params)
    {
        $eventsAlbumsTable = Api::_()->getDbTable('Event\DbTable\EventsAlbums');
        $eventsAlbumsTableName = $eventsAlbumsTable->initTableName()->getTable();
        
        if($params->event_id){
            $params->inEvent = true;
        }

        if($params->inEvent){
            $eventId = $params->event_id;

            $this->where(function($where) use ($eventsAlbumsTableName, $eventId){
                $select = new Select($eventsAlbumsTableName);
                $select->columns(array('album_id'));
                if($eventId){
                    $select->where(array(
                        'event_id' => $eventId
                    ));
                }
                
                $where->in('id', $select);

                return $where;
            });
        }
        return parent::setParameters($params);
    }
}
