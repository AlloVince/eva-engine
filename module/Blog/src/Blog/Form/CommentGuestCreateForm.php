<?php
namespace Blog\Form;

class CommentGuestCreateForm extends CommentCreateForm
{
    protected $mergeFilters = array (
        'email' => array (
            'required' => true,
        ),
        'user_name' => array (
            'required' => true,
        ),
    );
}
