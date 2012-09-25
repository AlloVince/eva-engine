<?php
namespace Blog\Form;

class PostCreateForm extends PostForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Text' => 'Blog\Form\TextForm',
            'CategoryPost' => 'Blog\Form\CategoryPostForm',
        ),
    );

    protected $mergeElements = array(

    );

    protected $mergeFilters = array(
        'title' => array(
            'required' => true,
        ),
        'urlName' => array (
            'required' => true,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'blog_posts',
                    ),
                ),
            ),
        ),
    );
}
