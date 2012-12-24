<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Blog\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class CommentForm extends \Eva\Form\Form
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
        'status' => array (
            'name' => 'status',
            'type' => 'select',
            'options' => array (
                'label' => 'Status',
                'value_options' => array (
                    'approved' => array (
                        'label' => 'Approved',
                        'value' => 'approved',
                    ),
                    'pending' => array (
                        'label' => 'Pending',
                        'value' => 'pending',
                    ),
                    'spam' => array (
                        'label' => 'Spam',
                        'value' => 'spam',
                    ),
                    'deleted' => array (
                        'label' => 'Deleted',
                        'value' => 'deleted',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'pending',
            ),
        ),
        'codeType' => array (
            'name' => 'codeType',
            'type' => 'radio',
            'options' => array (
                'label' => 'Code Type',
                'value_options' => array (
                    'markdown' => array (
                        'label' => 'Markdown',
                        'value' => 'markdown',
                    ),
                    'html' => array (
                        'label' => 'HTML',
                        'value' => 'html',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'markdown',
            ),
        ),
        'post_id' => array (
            'name' => 'post_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Post_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'user_name' => array (
            'name' => 'user_name',
            'type' => 'text',
            'options' => array (
                'label' => 'Your Name',
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
        'site' => array (
            'name' => 'site',
            'type' => 'text',
            'options' => array (
                'label' => 'Site',
            ),
            'attributes' => array (
                'value' => '',
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
        'parentId' => array (
            'name' => 'parentId',
            'type' => 'number',
            'options' => array (
                'label' => 'Parent Id',
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
                            'approved',
                            'pending',
                            'spam',
                            'deleted',
                        ),
                    ),
                ),
            ),
        ),
        'codeType' => array (
            'name' => 'codeType',
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
                        'max' => '30',
                    ),
                ),
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'markdown',
                            'html',
                            'wiki',
                            'reStructuredText',
                            'ubb',
                            'other',
                        ),
                    ),
                ),
            ),
        ),
        'post_id' => array (
            'name' => 'post_id',
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
        'user_name' => array (
            'name' => 'user_name',
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
                'emailAddress' => array (
                    'name' => 'EmailAddress',
                ),
            ),
        ),
        'site' => array (
            'name' => 'site',
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
                'uri' => array (
                    'name' => 'Uri',
                    'options' => array (
                        'allowRelative' => false,
                    ),
                ),
            ),
        ),
        'content' => array (
            'name' => 'content',
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
        'parentId' => array (
            'name' => 'parentId',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
