<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Fieldoption extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Fieldoptions';

    protected $relationships = array(

    );

    protected $map = array(
        'create' => array(
            //'getFieldId()'
        ),
    );

    public function create()
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

    public function save()
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


    public function remove()
    {
        if(isset($this[0])){
            $dataClass = $this->getDataClass();
            foreach($this as $item){
                $where = $item->getPrimaryArray();
                $dataClass->where($where)->remove();
            }
        } else {
            parent::remove();
        }
    }
}
