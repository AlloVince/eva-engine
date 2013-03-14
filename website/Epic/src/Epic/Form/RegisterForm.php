<?php
namespace Epic\Form;

use Eva\Api;

class RegisterForm extends \User\Form\UserForm
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
        'role',
        'code',
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
        'role' => array (
            'name' => 'role',
            'type' => 'radio',
            'options' => array (
                'label' => 'Register As',
                'value_options' => array (
                    /*
                    array (
                        'label' => 'Corporate Member',
                        'value' => 'CORPORATE_MEMBER',
                    ),
                    */
                    array (
                        'label' => 'Connoisseur',
                        'value' => 'CONNOISSEUR_MEMBER',
                    ),
                    array (
                        'label' => 'Professional',
                        'value' => 'PROFESSIONAL_MEMBER',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'CONNOISSEUR_MEMBER',
            ),
        ),
        'code' => array (
            'name' => 'code',
            'type' => 'text',
            'options' => array (
                'label' => 'Invite Code',
            ),
            'attributes' => array (
                'value' => '',
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
                        'min' => '5',
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
                    'name' => 'Identical',
                    'options' => array (
                        'token' => 'inputPassword',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => 'Password not match',
                        ),
                    ),

                ),
            ),
        ),
        'role' => array (
            'name' => 'role',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            //'CORPORATE_MEMBER',
                            'CONNOISSEUR_MEMBER',
                            'PROFESSIONAL_MEMBER',
                        ),
                    ),
                ),
            ),
        ),
        'code' => array (
            'name' => 'code',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );

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

        $roleKey = $data['role'] ? $data['role'] : 'CONNOISSEUR_MEMBER';
        $itemModel = Api::_()->getModel('User\Model\Role');
        $role = $itemModel->getRole($roleKey);
        $data['RoleUser'] = array(
            array(
                'user_id' => null,
                'role_id' => $role->id,
                'status' => 'active',
            )
        );
        unset($data['role']);
        unset($data['code']);
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }
}
