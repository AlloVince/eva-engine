<?php
namespace Scaffold\Form;

class AutoClassForm extends \Eva\Form\Form
{
    protected $mergeElements = array(
        'select_type' => array(
            'name' => 'inputType[]',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
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
                        'label' => 'Radio',
                        'value' => 'radio',
                    ),
                    array(
                        'label' => 'MultiCheckbox',
                        'value' => 'multiCheckbox',
                    ),
                    array(
                        'label' => 'Hidden',
                        'value' => 'hidden',
                    ),
                    array(
                        'label' => 'File',
                        'value' => 'file',
                    ),
                ),
                'label' => 'Select Type',
            ),
            'attributes' => array(
                'value' => 'text',
            ),
        ),
        'validator' => array(
            'name' => 'validators[]',
            'type' => 'multiCheckbox',
            'options' => array(
                'value_options' => array(
                    'NotEmpty' => 'NotEmpty',
                    'Uri' => 'Uri',
                    'StringLength' => 'StringLength',
                    'EmailAddress' => 'EmailAddress',
                    'InArray' => 'InArray',
                ),
            ),

            'attributes' => array(

            ),
        ),
        'filter' => array(
            'name' => 'filters[]',
            'type' => 'multiCheckbox',
            'options' => array(
                'value_options' => array(
                    'StripTags' => 'StripTags',
                    'StringTrim' => 'StringTrim',
                ),
            ),
            'attributes' => array(

            ),
        ),
    );
}
