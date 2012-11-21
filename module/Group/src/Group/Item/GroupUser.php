<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class GroupUser extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\GroupsUsers';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create()
    {
        $groupItem = $this->getModel()->getItem();
        $groupId = $groupItem->id;
        if(!$groupId || !$this->file_id) {
            return;
        }

        $data = $this->toArray();
        $data['group_id'] = $groupId;
        $dataClass = $this->getDataClass();
        $dataClass->create($data);
    }

    public function save()
    {
        $groupItem = $this->getModel()->getItem();
        $groupId = $groupItem->id;
        if(!$groupId) {
            return;
        }

        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'group_id' => $fieldId
        ))->remove();
        if(isset($this[0])){
            foreach($this as $item){
                $item['group_id'] = $groupId;
                $dataClass->create($item);
            }
        }
    }
}
