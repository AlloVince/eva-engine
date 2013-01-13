<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class RoleUser extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\RolesUsers';

    public function create($mapKey = 'create')
    {
        $userItem = $this->getModel()->getItem('User\Item\User');
        $userId = $userItem->id;
        if(!$userId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(isset($this[0])){
            foreach($this as $item){
                if(!$item['status']) {
                    continue;
                }
                $item['user_id'] = $userId;
                $dataClass->create($item);
            }
        }
    }

    public function save($mapKey = 'save')
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
                ))->remove();
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
    
    public function saveRoleUser()
    {
        if (!$this->user_id || !$this->role_id) {
            return;
        }
        
        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'user_id' => $this->user_id,
            'role_id' => $this->role_id,
        ))->save($this->toArray());
    }
}
