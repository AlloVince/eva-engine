<?php

namespace Video\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class MessagesVideos extends TableGateway
{
    protected $tableName = 'messages_videos';

    protected $primaryKey = array(
        'message_id',
        'video_id',
    );

    public function setParameters(Parameters $params)
    {
        return $this;
    }
}
