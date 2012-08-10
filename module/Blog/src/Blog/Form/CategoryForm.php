<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class CategoryForm extends Form
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
        'categoryName' => array (
            'name' => 'categoryName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Category Name',
                'value' => '',
            ),
        ),
        'urlName' => array (
            'name' => 'urlName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Category Url',
                'value' => '',
            ),
        ),
        'parentId' => array (
            'name' => 'parentId',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Parent Id',
                'options' => array(
                    array(
                        'label' => 'Select Parent',
                        'value' => '',
                    ),  
                ),
                'value' => '',
            ),
        ),
        'description' => array (
            'name' => 'description',
            'attributes' => array (
                'type' => 'textarea',
                'label' => 'Description',
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
        'parentId' => array (
            'name' => 'parentId',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
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
    );

}          
