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
class UserForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'id' => array (
            'name' => 'id',
            'attributes' => array (
                'type' => 'hidden',
                'label' => 'Id',
                'value' => '',
            ),
        ),
        'userName' => array (
            'name' => 'userName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'User Name',
                'value' => '',
            ),
        ),
        'email' => array (
            'name' => 'email',
            'attributes' => array (
                'type' => 'email',
                'label' => 'Email',
                'value' => '',
            ),
        ),
        'status' => array (
            'name' => 'status',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Status',
                'options' => array (
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
                'value' => 'active',
            ),
        ),
        'screenName' => array (
            'name' => 'screenName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Screen Name',
                'value' => '',
            ),
        ),
        'firstName' => array (
            'name' => 'firstName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'First Name',
                'value' => '',
            ),
        ),
        'lastName' => array (
            'name' => 'lastName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Last Name',
                'value' => '',
            ),
        ),
        'password' => array (
            'name' => 'password',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Password',
                'value' => '',
            ),
        ),
        'oldPassword' => array (
            'name' => 'oldPassword',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Old Password',
                'value' => '',
            ),
        ),
        'gender' => array (
            'name' => 'gender',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Gender',
                'options' => array (
                    array (
                        'label' => 'Male',
                        'value' => 'male',
                    ),
                    array (
                        'label' => 'Female',
                        'value' => 'female',
                    ),
                    array (
                        'label' => 'Other',
                        'value' => 'other',
                    ),
                ),
            ),
        ),
        'avatar' => array (
            'name' => 'avatar',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Avatar',
                'value' => '',
            ),
        ),
        'timezone' => array (
            'name' => 'timezone',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Timezone',
                'value' => '',
            ),
        ),
        'language' => array (
            'name' => 'language',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Language',
                'value' => 'zh_CN',
            ),
        ),
        'onlineStatus' => array (
            'name' => 'onlineStatus',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Online Status',
                'options' => array (
                    array (
                        'label' => 'Online',
                        'value' => 'online',
                    ),
                    array (
                        'label' => 'Busy',
                        'value' => 'busy',
                    ),
                    array (
                        'label' => 'Offline',
                        'value' => 'offline',
                    ),
                ),
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
                'emailAddress' => array (
                    'name' => 'EmailAddress',
                    'options' => array (
                    ),
                ),
            ),
        ),
        'status' => array (
            'name' => 'status',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'active',
                            'deleted',
                            'inactive',
                        ),
                    ),
                ),
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
                        'max' => '32',
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
                        'max' => '32',
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
                            'offline',
                        ),
                    ),
                ),
            ),
        ),
    );
}
