<?php
namespace Group\Form;

class GroupDeleteForm extends GroupForm
{
    protected $validationGroup = array('id');

    protected $baseFilters = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
