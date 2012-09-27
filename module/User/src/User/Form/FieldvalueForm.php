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

namespace User\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class FieldvalueForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'field_id' => array (
            'name' => 'field_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Field_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'user_id' => array (
            'name' => 'user_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'User_id',
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'value' => array (
            'name' => 'value',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Value',
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
        'field_id' => array (
            'name' => 'field_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'user_id' => array (
            'name' => 'user_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'value' => array (
            'name' => 'value',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
