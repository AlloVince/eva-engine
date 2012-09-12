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
        $dataClass = $this->getDataClass();
        $fieldId = $this->model->getDataSource();
        $data = $this->toArray(
            isset($this->map['create']) ? $this->map['create'] : array()
        );
        p($data);
        exit;
        $primaryKey = $dataClass->getPrimaryKey();
        if($dataClass->create($data)){
            $this->$primaryKey = $dataClass->getLastInsertValue();
        }
        return $this->$primaryKey;
        exit;
    }
}
