<?php
namespace User\Form;

class AdminLoginForm extends LoginForm
{
    protected $mergeElements = array(
        'isSuperAdmin' => array (
            'name' => 'isSuperAdmin',
            'type' => 'checkbox',
            'options' => array (
                'label' => 'Login As SuperAdmin',
            ),
            'attributes' => array (
                'value' => '1',
            ),
        ),
    );

    protected $mergeFilters = array(
        'isSuperAdmin' => array (
            'name' => 'isSuperAdmin',
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

