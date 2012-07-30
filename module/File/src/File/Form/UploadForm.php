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
class UploadForm extends \Eva\Form\Form
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
        'title' => array (
            'name' => 'title',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Title',
                'value' => '',
            ),
        ),
        'upload' => array (
            'name' => 'upload',
            'attributes' => array (
                'type' => 'file',
                'label' => 'Select File',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $baseFilters = array (
        'title' => array (
            'name' => 'title',
            'required' => false,
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
                        'max' => '100',
                    ),
                ),
            ),
        ),
        'upload' => array (
            'name' => 'upload',
            'required' => false,
            'filters' => array (
                array (
                    'name' => '\Eva\Filter\File\AutoRename',
                    'options' => array(
                        'pathkey' => 'default',
                    ),
                ),
            ),
            'validators' => array (
                array (
                    'name' => 'File\Extension',
                    'options' => array (
                        'extension' => array('txt'),
                    ),
                ),
            ),
        ),
    );
}

