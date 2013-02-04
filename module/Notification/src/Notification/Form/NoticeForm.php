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
class NoticeForm extends \Eva\Form\Form
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
        'message_id' => array (
            'name' => 'message_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Message_id',
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
                    'active' => array (
                        'label' => 'Active',
                        'value' => 'active',
                    ),
                    'deleted' => array (
                        'label' => 'Deleted',
                        'value' => 'deleted',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'active',
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
        'readFlag' => array (
            'name' => 'readFlag',
            'type' => 'number',
            'options' => array (
                'label' => 'Read Flag',
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'content' => array (
            'name' => 'content',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Content',
            ),
            'attributes' => array (
                'value' => '',
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
            ),
        ),
        'message_id' => array (
            'name' => 'message_id',
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
                        ),
                    ),
                ),
            ),
        ),
        'notification_id' => array (
            'name' => 'notification_id',
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
        'readFlag' => array (
            'name' => 'readFlag',
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
        'createTime' => array (
            'name' => 'createTime',
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
        'readTime' => array (
            'name' => 'readTime',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'content' => array (
            'name' => 'content',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
