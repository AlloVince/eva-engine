<?php

namespace Group\Item;

use Eva\Mvc\Item\AbstractItem;

class CategoryGroup extends AbstractItem
{
    protected $dataSourceClass = 'Group\DbTable\CategoriesGroups';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create($mapKey = 'create')
    {
        $groupItem = $this->getModel()->getItem('Group\Item\Group');
        $groupId = $groupItem->id;
        if(!$groupId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(count($this) > 0){
            foreach($this as $item){
                $item['group_id'] = $groupId;
                $dataClass->create($item);
            }
        }
    }

    public function save($mapKey = 'save')
    {
        $groupItem = $this->getModel()->getItem('Group\Item\Group');
        $groupId = $groupItem->id;
        if(!$groupId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(count($this) > 0){
            foreach($this as $item){
                $item['group_id'] = $groupId;
                $dataClass->where(array(
                    'group_id' => $groupId,
                    'category_id' => $item['category_id'],
                ))->remove();
                $dataClass->create($item);
            }
        }
    }
}
