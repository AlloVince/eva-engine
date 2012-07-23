<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class CategoryDeleteForm extends CategoryForm
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
