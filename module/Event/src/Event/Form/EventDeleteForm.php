<?php
namespace Event\Form;

class EventDeleteForm extends EventForm
{
    protected $validationGroup = array('id');

    protected $mergeFilters = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
