<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace User\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class UserForm extends \Eva\Form\RestfulForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Profile' => 'User\Form\ProfileForm',
            'Account' => 'User\Form\AccountForm',
            'RoleUser' => array(
                'formClass' => 'User\Form\RoleUserForm',
                'collection' => true,
            ),
            'CommonField' => 'User\Form\UserCommonFieldForm',
        ),
        'userfield' => array(
        ),
    );

    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'id' => array (
            'name' => 'id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'userName' => array (
            'name' => 'userName',
            'type' => 'text',
            'options' => array (
                'label' => 'User Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'email' => array (
            'name' => 'email',
            'type' => 'text',
            'options' => array (
                'label' => 'Email',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'status' => array (
            'name' => 'status',
            'type' => 'select',
            'options' => array (
                'label' => 'Status',
                'value_options' => array (
                    array (
                        'label' => 'Active',
                        'value' => 'active',
                    ),
                    array (
                        'label' => 'Deleted',
                        'value' => 'deleted',
                    ),
                    array (
                        'label' => 'Inactive',
                        'value' => 'inactive',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'active',
            ),
        ),
        'screenName' => array (
            'name' => 'screenName',
            'type' => 'text',
            'options' => array (
                'label' => 'Screen Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'firstName' => array (
            'name' => 'firstName',
            'type' => 'text',
            'options' => array (
                'label' => 'First Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'lastName' => array (
            'name' => 'lastName',
            'type' => 'text',
            'options' => array (
                'label' => 'Last Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'password' => array (
            'name' => 'password',
            'type' => 'text',
            'options' => array (
                'label' => 'Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'oldPassword' => array (
            'name' => 'oldPassword',
            'type' => 'text',
            'options' => array (
                'label' => 'Old Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'gender' => array (
            'name' => 'gender',
            'type' => 'select',
            'options' => array (
                'label' => 'Gender',
                'value_options' => array (
                    array (
                        'label' => 'Select Gender',
                        'value' => 'other',
                    ),
                    array (
                        'label' => 'Male',
                        'value' => 'male',
                    ),
                    array (
                        'label' => 'Female',
                        'value' => 'female',
                    ),
                    array (
                        'label' => 'Secret',
                        'value' => 'other',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'avatar' => array (
            'name' => 'avatar',
            'type' => 'text',
            'options' => array (
                'label' => 'Avatar',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'timezone' => array (
            'name' => 'timezone',
            'type' => 'select',
            'callback' => 'getTimezones',
            'options' => array (
                'label' => 'Timezone',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'language' => array (
            'name' => 'language',
            'type' => 'select',
            'callback' => 'getLanguages',
            'options' => array (
                'label' => 'Language',
            ),
            'attributes' => array (
                'value' => 'zh_CN',
            ),
        ),
        'onlineStatus' => array (
            'name' => 'onlineStatus',
            'type' => 'select',
            'options' => array (
                'label' => 'Online Status',
                'value_options' => array (
                    array (
                        'label' => 'Online',
                        'value' => 'online',
                    ),
                    array (
                        'label' => 'Busy',
                        'value' => 'busy',
                    ),
                    array (
                        'label' => 'Invisible',
                        'value' => 'invisible',
                    ),
                    array (
                        'label' => 'Offline',
                        'value' => 'offline',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'offline',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $baseFilters = array (
        'id' => array (
            'name' => 'id',
            'required' => false,
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
        'userName' => array (
            'name' => 'userName',
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
        'email' => array (
            'name' => 'email',
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
                        'max' => '320',
                    ),
                ),
            ),
        ),
        'status' => array (
            'name' => 'status',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'screenName' => array (
            'name' => 'screenName',
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
        'firstName' => array (
            'name' => 'firstName',
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
                        'max' => '20',
                    ),
                ),
            ),
        ),
        'lastName' => array (
            'name' => 'lastName',
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
                        'max' => '20',
                    ),
                ),
            ),
        ),
        'password' => array (
            'name' => 'password',
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
        'oldPassword' => array (
            'name' => 'oldPassword',
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
        'gender' => array (
            'name' => 'gender',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'male',
                            'female',
                            'other',
                        ),
                    ),
                ),
            ),
        ),
        'avatar' => array (
            'name' => 'avatar',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'timezone' => array (
            'name' => 'timezone',
            'required' => false,
            'callback' => 'getTimezones',
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
                        'max' => '64',
                    ),
                ),
            ),
        ),
        'language' => array (
            'name' => 'language',
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
                        'max' => '10',
                    ),
                ),
            ),
        ),
        'onlineStatus' => array (
            'name' => 'onlineStatus',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'online',
                            'busy',
                            'invisible',
                            'offline',
                        ),
                    ),
                ),
            ),
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
        if(isset($data['CommonField']) && is_array($data['CommonField'])){
            $fieldvalues = array();
            foreach($data['CommonField'] as $key => $fieldValue){
                if(!$fieldValue){
                    continue;
                }
                $fieldvalues[] = array(
                    'field_id' => $key,
                    'value' => $fieldValue,
                );
            }
            $data['CommonField'] = $fieldvalues;
        }

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
