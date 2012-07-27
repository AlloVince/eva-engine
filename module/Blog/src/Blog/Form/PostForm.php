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
                        'label' => 'Pending',
                        'value' => 'pending',
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

        'codeType' => array (
            'name' => 'codeType',
            'attributes' => array (
                'type' => 'radio',
                'label' => 'Code Type',
                'options' => array (
                    array (
                        'label' => 'Markdown',
                        'value' => 'markdown',
                    ),
                    array (
                        'label' => 'Html',
                        'value' => 'html',
                    ),
                    array (
                        'label' => 'Wiki',
                        'value' => 'wiki',
                    ),
                    array (
                        'label' => 'Ubb',
                        'value' => 'ubb',
                    ),
                    array (
                        'label' => 'Other',
                        'value' => 'other',
                    ),
                ),
                'value' => 'markdown',
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
            'required' => false,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => 100,
                    ),
                ),
                array(
                    'name' => 'DbNoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'eva_blog_posts',
                        'messages' => array(
                             \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Abc',
                        ), 
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

        'codeType' => array (
            'name' => 'codeType',
            'filters' => array (
            ),
            'validators' => array (
                array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'markdown',
                            'html',
                            'wiki',
                            'ubb',
                            'other',
                        ),
                    ),
                ),
            ),
        ),
   
        
    );
}
