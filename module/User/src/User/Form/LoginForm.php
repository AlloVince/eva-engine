<?php
namespace User\Form;

class LoginForm extends \Eva\Form\Form
{
    protected $subFormGroups = array(
        'default' => array(
        ),
    );

    protected $baseElements = array(
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
    );

    protected $baseFilters = array(
        'loginName' => array (
            'name' => 'loginName',
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
