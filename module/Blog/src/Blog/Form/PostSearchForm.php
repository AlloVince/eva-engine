<?php
namespace Blog\Form;

class PostSearchForm extends PostForm
{
    protected $mergeElements = array(
        'keyword' =>     array(
            'name' => 'keyword',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Keyword',
            ),
        ),
        'status' => array(
            'attributes' => array(
                'options' => array(
                    array(
                        'label' => 'Post Status',
                        'value' => '',
                    ),
                ),
                'value' => '',
            ),
        ),
        'visibility' => array(
            'attributes' => array(
                'options' => array(
                    array(
                        'label' => 'Select Visibility',
                        'value' => '',
                    ),  
                ),
                'value' => '',
            ),
        ),
        'category' => array(
            'name' => 'category',
            'label' => 'Category',
            'attributes' => array(
                'type' => 'select',
                'options' => array(
                    array(
                        'label' => 'Select Category',
                        'value' => '',
                    ),  
                ),
                'value' => '',
            ),
        ),
    );
}
