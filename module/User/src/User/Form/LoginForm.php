<?php
namespace User\Form;

class LoginForm extends \Eva\Form\Form
{
    protected $subFormGroups = array(
        'default' => array(
        ),
    );

    protected $mergeElements = array(
        'loginName' => array (
            'name' => 'loginName',
            'type' => 'text',
            'options' => array (
                'label' => 'User name or Email',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'inputPassword' => array (
            'name' => 'inputPassword',
            'type' => 'password',
            'options' => array (
                'label' => 'Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'rememberMe' => array (
            'name' => 'rememberMe',
            'type' => 'checkbox',
            'options' => array (
                'label' => 'Remember Me',
            ),
            'attributes' => array (
                'value' => '1',
            ),
        ),
    );

    protected $mergeFilters = array(
        'loginName' => array (
            'name' => 'loginName',
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
            ),
        ),
        'inputPassword' => array (
            'name' => 'inputPassword',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'min' => '6',
                        'max' => '16',
                    ),
                ),
            ),
        ),
        'rememberMe' => array (
            'name' => 'rememberMe',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );


    public function prepareData($data)
    {
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }
}
