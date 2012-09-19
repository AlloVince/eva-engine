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
            /*
            'RoleUser' => array(
                'formClass' => 'User\Form\RoleUserForm',
                'collection' => true,
            ),
            */
        ),
    );

    protected $mergeElements = array(
        'timezone' => array (
            'callback' => 'getTimezones',
        ),
        'language' => array (
            'callback' => 'getLanguages',
        ),
    );

    /*
    public function beforeBind($values)
    {
        $model = \Eva\Api::_()->getModelService('User\Model\Role');
        $roles = $model->getRoleList()->toArray();
        $roleUsers = array();
        if(isset($values['RoleUser']) && $values['RoleUser']){
            foreach($roles as $key => $role){
                $value = array(
                    'role_id' => $role['id']
                );
                foreach($values['RoleUser'] as $roleUser){
                    if($role['id'] == $roleUser['role_id']){
                        $value = $roleUser;
                    }
                }
                $roleUsers[] = $value;
            }
        } else {
            foreach($roles as $key => $role){
                $roleUsers[] = array(
                    'role_id' => $role['id']
                );
            }
        }
        $values['RoleUser'] = $roleUsers;
        return $values;
    }
    */

    public function prepareData($data)
    {
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }

    public function getLanguages($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $languages = \Eva\Locale\Data::getList($locale, 'language');
        $element['options']['value_options'] = $languages;
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
