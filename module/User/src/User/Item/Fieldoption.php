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
        $data = $this->toArray();
        $fieldItem = $this->getModel()->getItem('User\Item\Field');
        $fieldId = $fieldItem->id;

        if(!$fieldId) {
            return;
        }

        $dataClass = $this->getDataClass();
        if(isset($data[0])) {
            foreach($data as $key => $fieldOption){
                $fieldOption['field_id'] = $fieldId;
                $dataClass->create($fieldOption);
            }
        }
    }

    public function save()
    {
        $data = $this->toArray();
        $fieldItem = $this->getModel()->getItem('User\Item\Field');
        $fieldId = $fieldItem->id;

        if(!$fieldId) {
            return;
        }

        $dataClass = $this->getDataClass();
        $dataClass->where(array(
            'field_id' => $fieldId
        ))->remove();
        if(isset($data[0])) {
            foreach($data as $key => $fieldOption){
                $fieldOption['field_id'] = $fieldId;
                $dataClass->create($fieldOption);
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
