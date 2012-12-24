<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Activity\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class MessageVideoForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'message_id' => array (
            'name' => 'message_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Message_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'video_id' => array (
            'name' => 'video_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Video_id',
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
        'message_id' => array (
            'name' => 'message_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'video_id' => array (
            'name' => 'video_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
