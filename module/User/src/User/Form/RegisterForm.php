<?php
namespace User\Form;

class RegisterForm extends UserForm
{
    protected $subFormGroups = array(
        'default' => array(
        ),
    );

    protected $validationGroup = array(
        'userName',
        'email',
        'screenName',
        'inputPassword',
        'repeatPassword',
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
        'repeatPassword' => array (
            'name' => 'repeatPassword',
            'type' => 'password',
            'options' => array (
                'label' => 'Repeat Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
    );

    /*
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
            'required' => true,
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
        'repeatPassword' => array (
            'name' => 'repeatPassword',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
                'equalTo' => array(
                    'name' => 'Eva\Validator\EqualTo',
                    'injectdata' => true,
                    'options' => array (
                        'field' => 'inputPassword',
                    ),
                ),
            ),
        ),
    );
    */


    public function prepareData($data)
    {
        $data['status'] = 'inactive';
        if($data['inputPassword']) {
            $data['password'] = $data['inputPassword'];
            unset($data['inputPassword']);
            unset($data['repeatPassword']);
        }
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
