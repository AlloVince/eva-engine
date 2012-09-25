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
class FileConnectForm extends \Eva\Form\RestfulForm
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'file_id' => array (
            'name' => 'file_id',
            'type' => NULL,
            'options' => array (
                'label' => 'File_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'connect_id' => array (
            'name' => 'connect_id',
            'type' => NULL,
            'options' => array (
                'label' => 'Connect_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'connectType' => array (
            'name' => 'connectType',
            'type' => NULL,
            'options' => array (
                'label' => 'Connect Type',
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
        'file_id' => array (
            'name' => 'file_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'connect_id' => array (
            'name' => 'connect_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'connectType' => array (
            'name' => 'connectType',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
