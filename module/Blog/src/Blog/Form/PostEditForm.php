<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class PostEditForm extends PostCreateForm
{
    protected $mergeFilters = array(
        'urlName' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'blog_posts',
                        'field' => 'urlName',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                        'messages' => array(
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
