<?php
namespace Notification\Form;

class NoticeDeleteForm extends \Eva\Form\Form
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
                'label' => 'MessageId',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'user_id' => array (
            'name' => 'user_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'UserId',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
    );
  
    protected $validationGroup = array('message_id', 'user_id');

    protected $mergeFilters = array(
        'message_id' => array(
            'name' => 'message_id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
        'user_id' => array(
            'name' => 'user_id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
