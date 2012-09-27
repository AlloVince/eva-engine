<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class PostEditForm extends PostForm
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
        'urlName' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'table' => 'blog_posts',
                        'field' => 'urlName',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                        'messages' => array(
                            'recordFound' => 'Abc',
                        ), 
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
