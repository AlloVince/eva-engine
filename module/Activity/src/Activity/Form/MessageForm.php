<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Activity\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class MessageForm extends \Eva\Form\Form
{
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
        'messageType' => array (
            'name' => 'messageType',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Message Type',
                /*
                'value_options' => array (
                    array (
                        'label' => 'Original',
                        'value' => 'original',
                    ),
                    array (
                        'label' => 'Comment',
                        'value' => 'comment',
                    ),
                    array (
                        'label' => 'Forward',
                        'value' => 'forward',
                    ),
                ),
                */
            ),
            'attributes' => array (
                'value' => 'original',
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
        'reference_id' => array (
            'name' => 'reference_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Connect_id',
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
        'messageType' => array (
            'name' => 'messageType',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'original',
                            'comment',
                            'forward',
                        ),
                    ),
                ),
            ),
        ),
        'content' => array (
            'name' => 'content',
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
                        'max' => '140',
                        'encoding' => 'UTF-8',
                    ),
                ),
            ),
        ),
        'reference_id' => array (
            'name' => 'reference_id',
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

