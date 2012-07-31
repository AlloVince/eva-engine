<?php
namespace File\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class FileDeleteForm extends UploadForm
{
    protected $baseFilters = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
