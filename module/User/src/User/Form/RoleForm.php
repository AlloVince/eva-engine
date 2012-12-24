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
class RoleForm extends \Eva\Form\Form
{

    protected $subFormGroups = array(
        'default' => array(
        )
    );

    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
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
        'roleKey' => array (
            'name' => 'roleKey',
            'type' => 'text',
            'options' => array (
                'label' => 'Role Key',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'roleName' => array (
            'name' => 'roleName',
            'type' => 'text',
            'options' => array (
                'label' => 'Role Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'description' => array (
            'name' => 'description',
            'type' => 'text',
            'options' => array (
                'label' => 'Description',
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
    protected $mergeFilters = array (
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
        'roleKey' => array (
            'name' => 'roleKey',
            'required' => false,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
                'stringToUpper' => array(
                    'name' => 'StringToUpper'
                ),
                /*
                'wordSeparatorToSeparator' => array(
                    'name' => 'WordSeparatorToSeparator',
                    'options' => array(
                        'SearchSeparator' => ' ',
                        'ReplacementSeparator' => '_',
                    )
                ),
                */
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '50',
                    ),
                ),
            ),
        ),
        'roleName' => array (
            'name' => 'roleName',
            'required' => true,
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
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '200',
                    ),
                ),
            ),
        ),
    );
}
