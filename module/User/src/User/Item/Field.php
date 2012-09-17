<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Field extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Fields';

    protected $relationships = array(
        'Fieldoption' => array(
            'targetEntity' => 'User\Item\Fieldoption',
            'relationship' => 'OneToMany',
            'joinColumn' => 'field_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
                'limit' => false,
            ),
        ),
        'Fieldvalue' => array(
            'targetEntity' => 'User\Item\Fieldvalue',
            'relationship' => 'OneToOne',
            'joinColumn' => 'field_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'FieldRole' => array(
            'targetEntity' => 'User\Item\FieldRole',
            'relationship' => 'OneToMany',
            'joinColumn' => 'field_id',
            'referencedColumn' => 'id',
        ),
        'Roles' => array(
            'targetEntity' => 'User\Item\Role',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Roles',
            'joinColumns' => array(
                'joinColumn' => 'field_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'User\Item\FieldRole',
            'inversedMappedBy' => 'FieldRole',
            'inverseJoinColumns' => array(
                'joinColumn' => 'role_id',
                'referencedColumn' => 'id',
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getFieldKey()',
            'getFieldName()',
        ),
        'save' => array(
            'getFieldKey()',
            'getFieldName()',
        ),
    );

    public function getFieldKey()
    {
        if(!$this->fieldKey) {
            return $this->fieldKey = \Eva\Stdlib\String\Hash::uniqueHash();
        }
    }

    public function getFieldName()
    {
        if(!$this->fieldName) {
            return $this->fieldName = $this->label;
        }
    } 

}
