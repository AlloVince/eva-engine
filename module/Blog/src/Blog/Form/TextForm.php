<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class TextForm extends Form
{
    protected $fieldsMap = array(
        
    );

    protected $baseElements = array(
        'post_id' => array(
            'name' => 'post_id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ),

        'content' => array(
            'name' => 'content',
            'attributes' => array(
                'type'  => 'textarea',
                'label' => 'Content',
            ),
        ),

        'metaKeywords' => array(
            'name' => 'metaKeywords',
            'attributes' => array(
                'type'  => 'text',
                'label' => 'Meta Keywords',
            ),
        ),

        'metaDescription' => array(
            'name' => 'metaDescription',
            'attributes' => array(
                'type'  => 'textarea',
                'label' => 'Meta Description',
            ),
        )
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
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
            ),
        ),
        'metaDescription' => array (
            'name' => 'metaDescription',
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
            ),
        ),
        'content' => array (
            'name' => 'content',
            'required' => true,
            'filters' => array (
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
            ),
        ),
    );


}
