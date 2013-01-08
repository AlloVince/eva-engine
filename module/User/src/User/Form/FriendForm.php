<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
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
class FriendForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'user_id' => array (
            'name' => 'user_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'From User',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'friend_id' => array (
            'name' => 'friend_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'To User',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'relationshipStatus' => array (
            'name' => 'relationshipStatus',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Relationship Status',
            ),
            'attributes' => array (
                'value' => 'pending',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $mergeFilters = array (
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
        'friend_id' => array (
            'name' => 'friend_id',
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
        'relationshipStatus' => array (
            'name' => 'relationshipStatus',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'pending',
                        ),
                    ),
                ),
            ),
        ),
    );
}
