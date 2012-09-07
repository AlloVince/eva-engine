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
class FieldForm extends \Eva\Form\Form
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
        'fieldKey' => array (
            'name' => 'fieldKey',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Field Key',
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
        'description' => array (
            'name' => 'description',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Description',
                'value' => '',
            ),
        ),
        'applyToAll' => array (
            'name' => 'applyToAll',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Apply To All User',
                'options' => array (
                    array (
                        'label' => 'Yes',
                        'value' => '1',
                    ),
                    array (
                        'label' => 'No',
                        'value' => '0',
                    ),
                ),
                'value' => '1',
            ),
        ),
        'required' => array (
            'name' => 'required',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Required',
                'options' => array (
                    array (
                        'label' => 'Yes',
                        'value' => '1',
                    ),
                    array (
                        'label' => 'No',
                        'value' => '0',
                    ),
                ),
                'value' => '0',
            ),
        ),
        'defaultValue' => array (
            'name' => 'defaultValue',
            'attributes' => array (
                'type' => 'textarea',
                'label' => 'Default Value',
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
        'fieldKey' => array (
            'name' => 'fieldKey',
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
                        'max' => '24',
                    ),
                ),
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
                        'max' => '64',
                    ),
                ),
            ),
        ),
        'description' => array (
            'name' => 'description',
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
        'applyToAll' => array (
            'name' => 'applyToAll',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'notEmpty' => array (
                    'name' => 'NotEmpty',

                ),
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            '0',
                            '1',
                        ),
                    ),
                ),
            ),
        ),
        'required' => array (
            'name' => 'required',
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
        'defaultValue' => array (
            'name' => 'defaultValue',
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
