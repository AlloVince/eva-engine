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
class UploadForm extends FileForm
{

    protected $mergeElements = array (
        'upload' => array (
            'name' => 'upload',
            'type' => 'file',
            'options' => array(
                'label' => 'Select File',
            ),
            'attributes' => array (
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $mergeFilters = array (
        'upload' => array (
            'name' => 'upload',
            'required' => false,
            'options' => array(
                'ignoreNoFile' => true,
            ),
            'filters' => array (
                array (
                    'name' => '\Eva\Filter\File\AutoRename',
                    'options' => array(
                        'configkey' => 'default',
                    ),
                ),
            ),
            'validators' => array (
                /*`
                array (
                    'name' => 'File\Extension',
                    'options' => array (
                        'extension' => array('txt'),
                    ),
                ),
                */
            ),
        ),
    );
}

