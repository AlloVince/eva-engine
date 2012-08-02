<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class PostEditForm extends PostForm
{
    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'urlName' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExistsExcludeSelf',
                    'field' => 'urlName',
                    'table' => 'eva_blog_posts',
                    'options' => array(
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
}
