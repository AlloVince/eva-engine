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
class TextForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
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
        'metaKeywords' => array (
            'name' => 'metaKeywords',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Meta Keywords',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'metaDescription' => array (
            'name' => 'metaDescription',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Meta Description',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'toc' => array (
            'name' => 'toc',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Toc',
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
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $baseFilters = array (
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
        'metaKeywords' => array (
            'name' => 'metaKeywords',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'metaDescription' => array (
            'name' => 'metaDescription',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'toc' => array (
            'name' => 'toc',
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
            ),
        ),
    );
}

