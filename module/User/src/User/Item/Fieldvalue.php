<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Fieldvalue extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Fieldvalues';

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
                    'field_id' => $item['field_id'],
                ))->remove();
                $dataClass->create($item);
            }
        }
    }

}
