<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class GroupFile extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\GroupsFiles';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create($mapKey = 'create')
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

    public function save($mapKey = 'save')
    {
        $groupItem = $this->getModel()->getItem();
        $groupId = $groupItem->id;
        if(!$groupId || !$this->file_id) {
            return;
        }

        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'group_id' => $groupId
        ))->remove();
        $data = $this->toArray();
        $saveData['group_id'] = $groupId;
        $saveData['file_id'] = $data['file_id'];
        $dataClass->create($saveData);
    }
}
