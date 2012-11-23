<?php
namespace User\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class UserCreateForm extends UserForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Profile' => 'User\Form\ProfileForm',
            'Account' => 'User\Form\AccountForm',
            'RoleUser' => array(
                'formClass' => 'User\Form\RoleUserForm',
                'collection' => true,
                'optionsCallback' => 'initRoles',
            ),
        ),
    );

    protected $mergeElements = array(
        'timezone' => array (
            'callback' => 'getTimezones',
        ),
        'language' => array (
            'callback' => 'getLanguages',
        ),
        'inputPassword' => array (
            'name' => 'inputPassword',
            'type' => 'text',
            'options' => array (
                'label' => 'Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
    );

    protected $mergeFilters = array(
        'userName' => array (
            'required' => true,
        ),
        'email' => array (
            'required' => true,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'field' => 'email',
                        'table' => 'user_users',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                    ),
                ),
            ),
        ),
        'inputPassword' => array (
            'name' => 'inputPassword',
            'required' => false,
            'filters' => array (
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
                        'max' => '128',
                    ),
                ),
            ),
        ),
    );

    public function beforeBind($data)
    {
        //Data is array is for display
        if(isset($data['RoleUser']) && is_array($data)){
            $roleUsers = array();
            $subForms = $this->get('RoleUser');
            foreach($subForms as $key => $subForm){
                $roleUser = array();
                $role = $subForm->getRole()->toArray();
                $roleUser['role_id'] = $role['id'];
                foreach($data['RoleUser'] as $roleUserArray){
                    if($roleUser['role_id'] == $roleUserArray['role_id']){
                        $roleUser = array_merge($roleUser, $roleUserArray);
                        break;
                    }
                }
                $roleUsers[] = $roleUser;
            }
            $data['RoleUser'] = $roleUsers;
        }
        return $data;
    }

    public function prepareData($data)
    {
        if(isset($data['inputPassword'])){
            $data['password'] = $data['inputPassword'];
            unset($data['inputPassword']);
        }

        $roleUsers = array();
        if(isset($data['RoleUser']) && $data['RoleUser']){
            foreach($data['RoleUser'] as $roleUser){
                if(isset($roleUser['role_id']) && $roleUser['role_id']){
                    $roleUsers[] = $roleUser;
                }
            }
            $data['RoleUser'] = $roleUsers;
        }

        return $data;
    }

    public function getLanguages($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $languages = \Eva\Locale\Data::getList($locale, 'language');
        $element['options']['value_options'] = $languages;
        $element['attributes']['value'] = $locale;
        return $element;
    }

    public function getTimezones($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $languages = \Eva\Locale\Data::getList($locale, 'citytotimezone');
        $element['options']['value_options'] = $languages;
        return $element;
    }
}
