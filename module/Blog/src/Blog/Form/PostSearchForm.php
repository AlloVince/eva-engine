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
            'name' => 'status',
            'attributes' => array(
                'type' => 'select',
                'options' => array(
                    array(
                        'label' => 'Post Status',
                        'value' => '',
                    ),
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
                        'label' => 'Select Visibility',
                        'value' => '',
                    ),  
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

    );
}
