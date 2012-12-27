<?php
namespace Blog\Form;

class CommentCreateForm extends CommentForm
{

    public function prepareData($data)
    {
        $data['status'] = 'approved';
        return $data;
    }
}
