<?php
namespace Activity\Form;

class MessageCreateForm extends MessageForm
{
    protected $subFormGroups = array(
        'default' => array(
            'MessageFile' => 'Activity\Form\MessageFileForm',
            'MessageVideo' => 'Activity\Form\MessageVideoForm',
        ),
    );
}
