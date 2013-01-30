<?php

namespace Notification\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Usersettings extends TableGateway
{
    protected $tableName = 'usersettings';
    protected $primaryKey = 'user_id';

    public function setParameters(Parameters $params)
    {
        parent::setParameter($params);
        return $this;
    }
}
