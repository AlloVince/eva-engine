<?php
namespace Scaffold\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class AutoClassForm extends Form
{
    protected $mergeElements = array(
        'select_type' => array(
            'name' => 'select_type[]',
            'attributes' => array(
                'type' => 'select',
                'options' => array(
                    array(
                        'label' => 'Text',
                        'value' => 'text',
                    ),
                    array(
                        'label' => 'Textarea',
                        'value' => 'textarea',
                    ),
                    array(
                        'label' => 'Number',
                        'value' => 'number',
                    ),
                    array(
                        'label' => 'Datetime',
                        'value' => 'datetime',
                    ),
                    array(
                        'label' => 'Password',
                        'value' => 'password',
                    ),
                    array(
                        'label' => 'Email',
                        'value' => 'email',
                    ),
                    array(
                        'label' => 'Url',
                        'value' => 'url',
                    ),
                    array(
                        'label' => 'Select',
                        'value' => 'select',
                    ),
                    array(
                        'label' => 'Raido',
                        'value' => 'raido',
                    ),
                    array(
                        'label' => 'MultiCheckbox',
                        'value' => 'multiCheckbox',
                    ),
                    array(
                        'label' => 'Hidden',
                        'value' => 'hidden',
                    ),
                ),
                'label' => 'Select Type',
                'value' => 'text',
            ),
        ),
        'validtor' => array(
            'name' => 'validtors[]',
            'attributes' => array(
                'type' => 'multiCheckbox',
                'options' => array(
                    '' => 'Text',
                ),
            ),
        ),
    );
}
