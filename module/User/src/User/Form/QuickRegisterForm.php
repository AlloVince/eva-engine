<?php
namespace User\Form;

class QuickRegisterForm extends UserForm
{
    protected $subFormGroups = array(
        'default' => array(
        ),
    );

    protected $validationGroup = array(
        'userName',
        'email',
        'screenName',
    );

    protected $mergeElements = array(
        'email' => array (
            'type' => 'email',
        ),
        'screenName' => array (
            'options' => array (
                'label' => 'Nick Name',
            ),
        ),
    );

    protected $mergeFilters = array(
        'userName' => array (
            'required' => true,
            'validators' => array (
                'alnum' => array (
                    'name' => 'Alnum',
                ),
                'notNumber' => array (
                    'name' => 'Eva\Validator\NotNumber',
                ),
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'min' => '2',
                        'max' => '20',
                    ),
                ),
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'userName',
                        'table' => 'user_users',
                    ),
                ),
            ),
        ),
        'screenName' => array (
            'required' => false,
            'filters' => array(
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'min' => '2',
                        'max' => '15',
                    ),
                ),
            ),
        ),
        'email' => array (
            'required' => true,
            'validators' => array (
                'emailAddress' => array(
                    'name' => 'EmailAddress',
                ),
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'email',
                        'table' => 'user_users',
                    ),
                ),
            ),
        ),
    );

    public function prepareData($data)
    {
        $data['status'] = 'inactive';
        $data['Profile'] = array(
            'user_id' => null,
        );
        $data['Account'] = array(
            'user_id' => null,
        );
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }
}
