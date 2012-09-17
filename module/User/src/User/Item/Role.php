<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Role extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Roles';

    protected $relationships = array(
        'CommonFields' => array(
            'targetEntity' => 'User\Item\Field',
            'relationship' => 'OneToMany',
            'joinColumn' => 'role_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
                'applyToAll' => 1,
                'limit' => false,
            ),
        ),
        'RoleFields' => array(
            'targetEntity' => 'User\Item\Field',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Fields',
            'joinColumns' => array(
                'joinColumn' => 'role_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'User\Item\FieldRole',
            'inversedMappedBy' => 'FieldRole',
            'inverseJoinColumns' => array(
                'joinColumn' => 'field_id',
                'referencedColumn' => 'id',
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getRoleKey()',
        ),
        'save' => array(
            'getRoleKey()',
        ),
    );

    public function getRoleKey()
    {
        if(!$this->roleKey){
            $this->roleKey = preg_replace('/[^\w]/', '_', strtoupper($this->roleName));
        }
    }
}
