<?php
namespace File\Form;

class FileSearchForm extends FileEditForm
{
    protected $mergeElements = array(
        'keyword' =>     array(
            'name' => 'keyword',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Keyword',
            ),
        ),
    );
}
