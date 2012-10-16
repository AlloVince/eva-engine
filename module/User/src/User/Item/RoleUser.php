<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class RoleUser extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\RolesUsers';

    public function create()
    {
        $userItem = $this->getModel()->getItem('User\Item\User');
        $userId = $userItem->id;
        if(!$userId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(isset($this[0])){
            foreach($this as $item){
                $item['user_id'] = $userId;
                $dataClass->create($item);
            }
        }
    }
    
    public function createRoleUser()
    {
        if (!$this->user_id || !$this->role_id) {
            return;
        }
        
        $dataClass = $this->getDataClass();
        $item['user_id'] = $this->user_id;
        $dataClass->where(array(
            'user_id' => $this->user_id,
            'role_id' => $this->role_id,
        ))->remove();
        
        $dataClass->create($this->toArray());
    }

    public function save()
    {
        $userItem = $this->getModel()->getItem('User\Item\User');
        $userId = $userItem->id;

        if(!$userId) {
            return;
        }
        $dataClass = $this->getDataClass();
        if(isset($this[0])){
            foreach($this as $item){
                $item['user_id'] = $userId;
                $dataClass->where(array(
                    'user_id' => $userId,
                    'role_id' => $item['role_id'],
                ))->remove();
                $dataClass->create($item);
            }
        }
    }
}
