<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class PostDeleteForm extends PostForm
{
	protected $baseElements = array(
		'id' => array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
		),

		'title' => 	array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Post Title',
            ),
		),

		'status' => array(
            'name' => 'status',
            'attributes' => array(
                'type' => 'select',
				'options' => array(
					array(
						'label' => 'Draft',
						'value' => 'draft',
					),	
					array(
						'label' => 'Published',
						'value' => 'published',
					),	
				),
                'label' => 'Post Status',
            ),
		),

		'visibility' => array(
            'name' => 'visibility',
            'attributes' => array(
                'type' => 'select',
				'options' => array(
					array(
						'label' => 'Public',
						'value' => 'public',
					),	
					array(
						'label' => 'Private',
						'value' => 'private',
					),	
				),
                'label' => 'Post Visibility',
            ),
		),

		'content' => array(
			'name' => 'content',
			'attributes' => array(
				'type'  => 'textarea',
				'label' => 'Content',
			),
		),

		'codeType' => array(
			'name' => 'codeType',
			'attributes' => array(
				'type'  => 'radio',
				'label' => 'Code Type',
				'options' => array(
					'HTML' => 'html',
					'Wiki' => 'wiki',
				),
				'value' => array('html'),
			),
		),
	);

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
