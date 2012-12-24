<?php
namespace Blog\Form;

class CommentCreateForm extends CommentForm
{
    protected $mergeFilters = array (
        'post_id' => array (
            'required' => true,
        ),
        'user_name' => array (
            'required' => true,
        ),
    );
}
