<?php
namespace File\Form;

class FileDeleteForm extends FileForm
{
    protected $validationGroup = array('id');

    protected $mergeFilters = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
