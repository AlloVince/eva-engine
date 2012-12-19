<?php
namespace Group\Form;

class CategoryDeleteForm extends CategoryForm
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
