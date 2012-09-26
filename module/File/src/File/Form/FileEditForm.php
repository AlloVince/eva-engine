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

namespace File\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class FileEditForm extends FileForm
{
    /**
     * Form basic elements
     *
     * @var array
     */
     protected $mergeElements = array (
        'originalName' => array (
            'name' => 'originalName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Original Name',
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
    protected $mergeFilters = array (
        'originalName' => array (
            'name' => 'originalName',
            'filters' => array (
                array (
                    'name' => 'StripTags',
                ),
                array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'description' => array (
            'name' => 'description',
            'filters' => array (
                array (
                    'name' => 'StripTags',
                ),
                array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '255',
                    ),
                ),
            ),
        ),
    );
}

