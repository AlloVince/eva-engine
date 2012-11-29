<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class FieldRole extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\FieldsRoles';

    protected $map = array(
        'create' => array(
        ),
    );

    public function create($mapKey = 'create')
    {
        $fieldItem = $this->getModel()->getItem();
        $fieldId = $fieldItem->id;
        if(!$fieldId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(isset($this[0])){
            foreach($this as $item){
                $item['field_id'] = $fieldId;
                $dataClass->create($item);
            }
        }
    }

    public function save($mapKey = 'save')
    {
        $fieldItem = $this->getModel()->getItem();
        $fieldId = $fieldItem->id;

        if(!$fieldId) {
            return;
        }

        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'field_id' => $fieldId
        ))->remove();
        if(isset($this[0])){
            foreach($this as $item){
                $item['field_id'] = $fieldId;
                $dataClass->create($item);
            }
        }
    }
}
