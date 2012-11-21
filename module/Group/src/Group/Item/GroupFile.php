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
            'group_id' => $groupId
        ))->remove();
        $data = $this->toArray();
        $data['group_id'] = $groupId;
        $dataClass->create($data);
    }
}
