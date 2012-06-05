<?php
namespace User\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class AdminLoginForm extends Form
{
	protected $baseElements = array(
		'userName' => 	array(
            'name' => 'userName',
            'attributes' => array(
                'type' => 'text',
                'label' => 'User Name',
            ),
		),
		'password' => array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'label' => 'Password',
            ),
		),
	);

	protected $baseFilters = array(
		'userName' => 	array(
			'name' => 'userName',
			'required' => true,
			'filters' => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min' => 1,
						'max' => 100,
					),
				),
			),
		),
		'password' => array(
            'name' => 'password',
            'required' => true,
		),
	);
}
