<?php
namespace Blog\Form;

class PostCreateForm extends PostForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Text' => 'Blog\Form\TextForm',
            'CategoryPost' => 'Blog\Form\CategoryPostForm',
            'FileConnect' => 'File\Form\FileConnectForm',
            'Tags' => array(
                'formClass' => 'Blog\Form\TagsForm',
                'collection' => true,
            ),
        ),
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

    public function beforeBind($data)
    {
        if(isset($data['Tags'][0]['tagName'])){
            $tagString = $data['Tags'][0]['tagName'];
            $tags = array();
            if(false === strpos($tagString, ',')) {
                $tags[] = array(
                    'tagName' => $tagString
                );
            } else {
                $tagNames = explode(',', $tagString);
                foreach($tagNames as $tag){
                    $tags[] = array(
                        'tagName' => $tag
                    );
                }
            }
            $data['Tags'] = $tags;
        }

        return $data;
    }

    public function prepareData($data)
    {
        if(isset($data['FileConnect'])){
            $data['FileConnect']['connect_id'] = $data['id'];
            $data['FileConnect']['connectType'] = 'PostCover';
        }



        return $data;
    }
}
