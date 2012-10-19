<?php
namespace Message\Form;

class ConversationReplyForm extends ConversationForm
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'recipient_id' => array (
            'name' => 'recipient_id',
            'type' => 'hidden',
            'options' => array (
                'label' => '',
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
        'recipient_id' => array (
            'name' => 'recipient_id',
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
