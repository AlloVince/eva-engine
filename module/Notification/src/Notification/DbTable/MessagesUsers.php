<?php

namespace Notification\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class MessagesUsers extends TableGateway
{
    protected $tableName = 'messages_users';
    protected $primaryKey = array('message_id', 'user_id', 'noticeType');

    public function setParameters(Parameters $params)
    {
        parent::setParameters($params);
        return $this;
    }
}
