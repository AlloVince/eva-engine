<?php
namespace User\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class AdminLoginForm extends Form
{
    protected $baseElements = array(
        'userName' =>     array(
            'name' => 'userName',
            'attributes' => array(
                'type' => 'text',
                'label' => 'User Name',
            ),
        ),
        'password' => array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'label' => 'Password',
            ),
        ),
    );

    protected $baseFilters = array(
        'userName' =>     array(
            'name' => 'userName',
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 100,
                    ),
                ),
            ),
        ),
        'password' => array(
            'name' => 'password',
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 32,
                    ),
                ),
            ),
        ),
    );
}
