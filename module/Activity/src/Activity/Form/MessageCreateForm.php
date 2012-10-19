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

    protected $mergeFilters = array(
        'content' => array(
            'required' => true,
        ),
    );

    public function prepareData($data)
    {
        return $data;
    }
}
