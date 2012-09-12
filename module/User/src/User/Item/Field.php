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
    );

    protected $map = array(
        'create' => array(
        ),
    );

}
