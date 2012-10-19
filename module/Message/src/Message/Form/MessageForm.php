<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Message\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class MessageForm extends \Eva\Form\Form
{
    protected $subFormGroups = array(
        'default' => array(
            'Conversation' => 'Message\Form\ConversationForm',
        ),
    );
    
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'body' => array (
            'name' => 'body',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Message',
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
        'body' => array (
            'name' => 'body',
            'required' => true,
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

