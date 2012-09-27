<?php
namespace Blog\Form;

class PostCreateForm extends PostForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Text' => 'Blog\Form\TextForm',
            'CategoryPost' => 'Blog\Form\CategoryPostForm',
            'FileConnect' => 'File\Form\FileConnectForm',
        ),
    );

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'title' => array(
            'required' => true,
        ),
        'urlName' => array (
            'required' => false,
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

    public function prepareData($data)
    {
        if(isset($data['FileConnect'])){
            $data['FileConnect']['connect_id'] = $data['id'];
            $data['FileConnect']['connectType'] = 'PostCover';
        }

        return $data;
    }
}
