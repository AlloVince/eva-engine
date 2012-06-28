<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class TextForm extends Form
{
    protected $fieldsMap = array(
        
    );

    protected $baseElements = array(
        'content' => array(
            'name' => 'content',
            'attributes' => array(
                'type'  => 'textarea',
                'label' => 'Content',
            ),
        ),

        'metaKeywords' => array(
            'name' => 'metaKeywords',
            'attributes' => array(
                'type'  => 'text',
                'label' => 'Meta Keywords',
            ),
        ),

        'metaDescription' => array(
            'name' => 'metaDescription',
            'attributes' => array(
                'type'  => 'textarea',
                'label' => 'Meta Description',
            ),
        )
    );

    protected $baseFilters = array(
        'content' => array(
            'name' => 'content',
            'required' => true,
        ),
    );
}
