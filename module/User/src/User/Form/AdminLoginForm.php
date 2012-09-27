<?php
namespace User\Form;

class AdminLoginForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'userName' => array (
            'name' => 'userName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'User Name',
                'value' => '',
            ),
        ),
        'password' => array (
            'name' => 'password',
            'attributes' => array (
                'type' => 'password',
                'label' => 'Password',
                'value' => '',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $baseFilters = array (
        'userName' => array (
            'name' => 'userName',
            'required' => true,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '128',
                    ),
                ),
            ),
        ),
        'password' => array (
            'name' => 'password',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
            ),
        ),
    );
}
