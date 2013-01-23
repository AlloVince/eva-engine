<?php
namespace Blog\Form;

class PostSearchForm extends PostForm
{
    protected $mergeElements = array(
        'keyword' =>     array(
            'name' => 'keyword',
            'type' => 'text',
            'options' => array(
                'label' => 'Keyword',
            ),
            'attributes' => array(
            ),
        ),
        'page' =>     array(
            'name' => 'page',
            'type' => 'text',
            'options' => array(
                'label' => 'Page',
            ),
            'attributes' => array(
                'value' => 1,
            ),
        ),
        'user_id' =>     array(
            'name' => 'user_id',
            'type' => 'hidden',
            'options' => array(
                'label' => 'User Id',
            ),
            'attributes' => array(
            ),
        ),
        'order' =>     array(
            'name' => 'order',
            'type' => 'text',
            'options' => array(
                'label' => 'order',
            ),
            'attributes' => array(
            ),
        ),
        'status' => array(
            'options' => array(
                'empty_option' => 'Post Status',
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'visibility' => array(
            'options' => array(
                'empty_option' => 'Select Visibility',
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),

        'category' => array(
            'name' => 'category',
            'type' => 'select',
            'options' => array(
                'label' => 'Category',
                'empty_option' => 'Select Category',
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),

        'tag' => array(
            'name' => 'tag',
            'type' => 'text',
            'options' => array(
                'label' => 'Text',
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),

        'rows' =>     array(
            'name' => 'rows',
            'type' => 'text',
            'options' => array(
                'label' => 'Rows',
            ),
            'attributes' => array(
                'value' => 10,
            ),
        ),
    );

    protected $mergeFilters = array(
        'category' => array(
            'name' => 'category',
            'required' => false,
        ),
    );

    public function prepareData($data)
    {
        if(!$data['page']){
            $data['page'] = 1;
        }

        if(!$data['order']) {
            $data['order'] = 'iddesc';
        }

        return $data;
    }
}
