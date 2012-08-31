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
class AccountForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'user_id' => array (
            'name' => 'user_id',
            'attributes' => array (
                'type' => 'hidden',
                'label' => 'User_id',
                'value' => '',
            ),
        ),
        'credits' => array (
            'name' => 'credits',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Credits',
                'value' => '0.00',
            ),
        ),
        'points' => array (
            'name' => 'points',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Points',
                'value' => '0.00',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $baseFilters = array (
        'user_id' => array (
            'name' => 'user_id',
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
        'credits' => array (
            'name' => 'credits',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'points' => array (
            'name' => 'points',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
