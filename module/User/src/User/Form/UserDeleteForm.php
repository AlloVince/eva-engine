<?php
namespace User\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class UserDeleteForm extends UserForm
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
