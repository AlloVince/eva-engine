<?php

namespace Message\Item;

use Eva\Mvc\Item\AbstractItem;

class Message extends AbstractItem
{
    protected $dataSourceClass = 'Message\DbTable\Messages';

    protected $relationships = array(
        'Conversation' => array(
            'targetEntity' => 'Message\Item\Conversation',
            'relationship' => 'OneToOne',
            'joinColumn' => 'message_id',
            'referencedColumn' => 'id',
        ),
    );

    protected $map = array(
    );
}
