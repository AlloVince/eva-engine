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
            'referencedColumn' => 'to_user_id',
        ),
    );

    protected $map = array(
        'create' => array(
            'getRequestTime()',
        ),
    );


    public function getRequestTime()
    {
        if(!$this->requestTime) {
            return $this->requestTime = \Eva\Date\Date::getNow();
        }
    }

    public function getApprovalTime()
    {
        if(!$this->approvalTime) {
            return $this->approvalTime = \Eva\Date\Date::getNow();
        }
    }

    public function getRefusedTime()
    {
        if(!$this->refusedTime) {
            return $this->refusedTime = \Eva\Date\Date::getNow();
        }
    }

    public function getBlockedTime()
    {
        if(!$this->blockedTime) {
            return $this->blockedTime = \Eva\Date\Date::getNow();
        }
    }

}
