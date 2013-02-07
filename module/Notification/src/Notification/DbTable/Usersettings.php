<?php

namespace Notification\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Usersettings extends TableGateway
{
    protected $tableName = 'usersettings';
    protected $primaryKey = array('user_id', 'notification_id');

    public function setParameters(Parameters $params)
    {
        parent::setParameters($params);
        return $this;
    }
}
