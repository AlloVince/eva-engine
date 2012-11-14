<?php
namespace User\Form;

use Eva\Form\Form;

class ResetForm extends Form
{
    protected $baseElements = array(
        'email' => array (
            'name' => 'email',
            'type' => 'text',
            'options' => array (
                'label' => 'Email',
            ),
            'attributes' => array (
                'value' => '',
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
                'emailAddress' => array(
                    'name' => 'EmailAddress',
                ),
                'db' => array(
                    'name' => 'Eva\Validator\Db\RecordExists',
                    'options' => array(
                        'field' => 'email',
                        'table' => 'user_users',
                    ),
                ),
            ),
        ),

    );
}
