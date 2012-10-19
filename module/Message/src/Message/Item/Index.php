<?php

namespace Message\Item;

use Eva\Mvc\Item\AbstractItem;

class Index extends AbstractItem
{
    protected $dataSourceClass = 'Message\DbTable\Indexes';

    protected $relationships = array(
       'Conversation' => array(
            'targetEntity' => 'Message\Item\Conversation',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'conversation_id',
        ),
        'Author' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'author_id',
        ),
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'user_id',
        ),
    );

    protected $map = array(
    );
}
