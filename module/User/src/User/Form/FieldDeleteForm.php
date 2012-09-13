<?php
namespace User\Form;

class FieldDeleteForm extends FieldForm
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
