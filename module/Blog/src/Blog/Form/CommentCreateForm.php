<?php
namespace Blog\Form;

class CommentCreateForm extends CommentForm
{
    protected $mergeElements = array(
    );

    protected $mergeFilters = array (
        'post_id' => array (
            'name' => 'post_id',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
