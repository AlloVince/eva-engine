<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Notification\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class UsersettingForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'user_id' => array (
            'name' => 'user_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'User_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'notification_id' => array (
            'name' => 'notification_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Notification_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'disableNotice' => array (
            'name' => 'disableNotice',
            'type' => 'radio',
            'options' => array (
                'label' => 'Disable Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '1',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '0',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'disableEmail' => array (
            'name' => 'disableEmail',
            'type' => 'radio',
            'options' => array (
                'label' => 'Disable Email',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '1',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '0',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'disableSms' => array (
            'name' => 'disableSms',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '1',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '0',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'disableAppleOsPush' => array (
            'name' => 'disableAppleOsPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '1',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '0',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'disableAndroidPush' => array (
            'name' => 'disableAndroidPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '1',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '0',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'disableWindowsPush' => array (
            'name' => 'disableWindowsPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '1',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '0',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'disableCustomNotice' => array (
            'name' => 'disableCustomNotice',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '1',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '0',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $mergeFilters = array (
        'user_id' => array (
            'name' => 'user_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'notification_id' => array (
            'name' => 'notification_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'disableNotice' => array (
            'name' => 'disableNotice',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
        'disableEmail' => array (
            'name' => 'disableEmail',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
        'disableSms' => array (
            'name' => 'disableSms',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
        'disableAppleOsPush' => array (
            'name' => 'disableAppleOsPush',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
        'disableAndroidPush' => array (
            'name' => 'disableAndroidPush',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
        'disableWindowsPush' => array (
            'name' => 'disableWindowsPush',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
        'disableCustomNotice' => array (
            'name' => 'disableCustomNotice',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
    );
}

