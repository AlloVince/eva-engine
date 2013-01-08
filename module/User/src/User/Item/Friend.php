<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Friend extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Friends';

    protected $relationships = array(
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'friend_id',
        ),
    );

    protected $map = array(
    );


    public function getRequestTime()
    {
        return $this->requestTime = \Eva\Date\Date::getNow();
    }

    public function getApprovalTime()
    {
        return $this->approvalTime = \Eva\Date\Date::getNow();
    }

    public function getRefusedTime()
    {
        return $this->refusedTime = \Eva\Date\Date::getNow();
    }

    public function getBlockedTime()
    {
        return $this->blockedTime = \Eva\Date\Date::getNow();
    }

    public function getRemovedTime()
    {
        return $this->removedTime = \Eva\Date\Date::getNow();
    }

}
