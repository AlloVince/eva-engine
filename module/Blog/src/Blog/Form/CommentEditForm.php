<?php
namespace Blog\Form;

class CommentEditForm extends CommentForm
{
    protected $mergeFilters = array (
        'id' => array (
            'required' => true,
        ),
        'post_id' => array (
            'required' => true,
        ),
    );
}
