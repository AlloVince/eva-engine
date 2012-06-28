<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class PostForm extends Form
{
    protected $fieldsMap = array(
        
    );

    protected $baseElements = array(
        'id' => array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ),

        'title' =>     array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Post Title',
            ),
        ),

        'urlName' =>     array(
            'name' => 'urlName',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Post Url',
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
                    'Markdown' => 'markdown',
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

        'title' =>     array(
            'name' => 'title',
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

        'urlName' =>     array(
            'name' => 'urlName',
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
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/\w+/'
                    ),
                ),
            ),
        ),

        'status' => array(
            'name' => 'status',
            'required' => true,
        ),

        'visibility' => array(
            'name' => 'visibility',
            'required' => true,
        ),

        /*
        'content' => array(
            'name' => 'content',
            'required' => true,
        ),
        */

        'codeType' => array(
            'name' => 'codeType',
            'required' => true,
        ),        
        
    );
}
