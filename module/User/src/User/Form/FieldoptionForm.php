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
class FieldoptionForm extends \Eva\Form\Form
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
        'label' => array (
            'name' => 'label',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Label',
                'value' => '',
            ),
        ),
        'option' => array (
            'name' => 'option',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Option',
                'value' => '',
            ),
        ),
        'order' => array (
            'name' => 'order',
            'attributes' => array (
                'type' => 'number',
                'label' => 'Order',
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
            ),
        ),
        'label' => array (
            'name' => 'label',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'option' => array (
            'name' => 'option',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'order' => array (
            'name' => 'order',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
