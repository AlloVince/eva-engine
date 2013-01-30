<?php

namespace Notification\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Notifications extends TableGateway
{
    protected $tableName = 'notifications';
    protected $primaryKey = 'id';
    protected $uniqueIndex = array(
        'notificationKey',
    );

    public function setParameters(Parameters $params)
    {
        if($params->id){
            if(is_array($params->id)){
                $this->where(array('id' => array_unique($params->id)));
            } else {
                $this->where(array('id' => $params->id));
            }
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('title', "%$keyword%");
                return $where;
            });
        }

        parent::setParameters($params);
        return $this;
    }
}
