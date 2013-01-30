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
class NotificationForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
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
        'notificationKey' => array (
            'name' => 'notificationKey',
            'type' => 'text',
            'options' => array (
                'label' => 'Notification Key',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'title' => array (
            'name' => 'title',
            'type' => 'text',
            'options' => array (
                'label' => 'Title',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'sendNotice' => array (
            'name' => 'sendNotice',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'sendEmail' => array (
            'name' => 'sendEmail',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Email',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'sendSms' => array (
            'name' => 'sendSms',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Sms',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'sendAppleOsPush' => array (
            'name' => 'sendAppleOsPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Apple Os Push',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'sendAndroidPush' => array (
            'name' => 'sendAndroidPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Android Push',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'sendWindowsPush' => array (
            'name' => 'sendWindowsPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Windows Push',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'sendCustomNotice' => array (
            'name' => 'sendCustomNotice',
            'type' => 'radio',
            'options' => array (
                'label' => 'Send Custom Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'allowDisableNotice' => array (
            'name' => 'allowDisableNotice',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Disable Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'allowDisableEmail' => array (
            'name' => 'allowDisableEmail',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Disable Email',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'allowDisableSms' => array (
            'name' => 'allowDisableSms',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Disable Sms',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'allowDisableAppleOsPush' => array (
            'name' => 'allowDisableAppleOsPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Disable Apple Os Push',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'allowDisableAndroidPush' => array (
            'name' => 'allowDisableAndroidPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Disable Android Push',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'allowDisableWindowsPush' => array (
            'name' => 'allowDisableWindowsPush',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Disable Windows Push',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'allowDisableCustomNotice' => array (
            'name' => 'allowDisableCustomNotice',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Disable Custom Notice',
                'value_options' => array (
                    '0' => array (
                        'label' => 'Disable',
                        'value' => '0',
                    ),
                    '1' => array (
                        'label' => 'Enable',
                        'value' => '1',
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
        'notificationKey' => array (
            'name' => 'notificationKey',
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
                        'max' => '50',
                    ),
                ),
            ),
        ),
        'title' => array (
            'name' => 'title',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'sendNotice' => array (
            'name' => 'sendNotice',
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
        'sendEmail' => array (
            'name' => 'sendEmail',
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
        'sendSms' => array (
            'name' => 'sendSms',
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
        'sendAppleOsPush' => array (
            'name' => 'sendAppleOsPush',
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
        'sendAndroidPush' => array (
            'name' => 'sendAndroidPush',
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
        'sendWindowsPush' => array (
            'name' => 'sendWindowsPush',
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
        'sendCustomNotice' => array (
            'name' => 'sendCustomNotice',
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
        'allowDisableNotice' => array (
            'name' => 'allowDisableNotice',
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
        'allowDisableEmail' => array (
            'name' => 'allowDisableEmail',
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
        'allowDisableSms' => array (
            'name' => 'allowDisableSms',
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
        'allowDisableAppleOsPush' => array (
            'name' => 'allowDisableAppleOsPush',
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
        'allowDisableAndroidPush' => array (
            'name' => 'allowDisableAndroidPush',
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
        'allowDisableWindowsPush' => array (
            'name' => 'allowDisableWindowsPush',
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
        'allowDisableCustomNotice' => array (
            'name' => 'allowDisableCustomNotice',
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
    );
}
