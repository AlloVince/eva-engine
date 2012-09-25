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
class CategoryForm extends \Eva\Form\RestfulForm
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
        'categoryName' => array (
            'name' => 'categoryName',
            'type' => 'text',
            'options' => array (
                'label' => 'Category Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'urlName' => array (
            'name' => 'urlName',
            'type' => 'text',
            'options' => array (
                'label' => 'Url Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'description' => array (
            'name' => 'description',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Description',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'parentId' => array (
            'name' => 'parentId',
            'type' => 'select',
            'options' => array (
                'label' => 'Parent Id',
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
        'categoryName' => array (
            'name' => 'categoryName',
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
                        'max' => '100',
                    ),
                ),
            ),
        ),
        'urlName' => array (
            'name' => 'urlName',
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
                        'max' => '100',
                    ),
                ),
            ),
        ),
        'description' => array (
            'name' => 'description',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
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

