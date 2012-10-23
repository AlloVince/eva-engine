<?php
namespace Video\Form;

class MessageCreateForm extends MessageForm
{
    protected $subFormGroups = array(
        'default' => array(
            'MessageFile' => 'Video\Form\MessageFileForm',
            'MessageVideo' => 'Video\Form\MessageVideoForm',
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
