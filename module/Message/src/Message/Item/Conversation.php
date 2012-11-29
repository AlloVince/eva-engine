<?php

namespace Message\Item;

use Eva\Mvc\Item\AbstractItem;

class Conversation extends AbstractItem
{
    protected $dataSourceClass = 'Message\DbTable\Conversations';

    protected $relationships = array(
        'Message' => array(
            'targetEntity' => 'Message\Item\Message',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'message_id',
        ),
        'Sender' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'sender_id',
        ),
        'Recipient' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'recipient_id',
        ),
    );

    protected $map = array(
        'create' => array(
            'getCreateTime()',
            'getSenderId()',
        ),
    );

    public function create($mapKey = 'create')
    {
        $messageItem = $this->getModel()->getItem('Message\Item\Message');
        $messageId = $messageItem->id;
        if(!$messageId) {
            return;
        }
        $dataClass = $this->getDataClass();
        
        $senderId = $this->getSenderId();
        $recipientId = $this->recipient_id;

        $authors = array($senderId, $recipientId);
        
        foreach ($authors as $author) {
            $item = array();
            $item['author_id'] = $author;
            $item['user_id'] = $author == $senderId ? $recipientId : $senderId;
            $item['sender_id'] = $senderId;
            $item['recipient_id'] = $recipientId;
            $item['createTime'] = $this->getCreateTime();
            $item['message_id'] = $messageId;
            
            if ($author == $senderId) {
                $item['readFlag'] = 1;
            }
            
            $dataClass->create($item);
        }   
    }

    public function getCreateTime()
    {
        if(!$this->createTime) {
            return $this->createTime = \Eva\Date\Date::getNow();
        } else {
            return $this->createTime;
        }
    }

    public function getSenderId()
    {
        if(!$this->sender_id){
            $user = \Core\Auth::getLoginUser();
            return $this->sender_id = $user['id'];
        } else {
            return $this->sender_id;
        }
    }
}
