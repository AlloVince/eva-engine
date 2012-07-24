<?php
namespace Scaffold\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class PostForm extends Form
{
   protected $mergeElements = array(
        'status' => array(
            'name' => 'select_type[]',
            'attributes' => array(
                'type' => 'select',
                'options' => array(
                    array(
                        'label' => 'Raido',
                        'value' => 'raido',
                    ),
                    array(
                        'label' => 'Select',
                        'value' => 'select',
                    ),
                ),
                'label' => 'Select Type',
                'value' => 'select',
            ),
        ),
    );
}
