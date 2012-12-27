<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class GroupUser extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\GroupsUsers';
    
    protected $relationships = array(
        'Group' => array(
            'targetEntity' => 'Group\Item\Group',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'group_id',
        ),
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'user_id',
        ),
    );

    protected $map = array(
        'create' => array(
            'getRequestTime()',
            'getApprovalTime()',
        ),
        'createAdmin' => array(
            'getAdminValues()',
            'getRequestTime()',
            'getApprovalTime()',
        ),
    );

    public function getAdminValues()
    {
        $this->role          = 'admin';
        $this->operator_id   = $this->user_id;
        $this->requestStatus = 'active';
    }

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
}
