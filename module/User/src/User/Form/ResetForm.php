<?php
namespace User\Form;

use Eva\Form\Form;

class ResetForm extends Form
{
    protected $baseElements = array(
        'email' =>     array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'label' => 'Email',
            ),
        ),
    );

    protected $baseFilters = array(
        'email' =>     array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                ),
                array(
                    'name' => 'EmailAddress',
                ),
            ),
        ),

    );
}
