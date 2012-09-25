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
                'value_options' => array(
                    array(
                        'label' => 'Post Status',
                        'value' => '',
                    ),
                ),
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'visibility' => array(
            'options' => array(
                'value_options' => array(
                    array(
                        'label' => 'Select Visibility',
                        'value' => '',
                    ),  
                ),
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
                'value_options' => array(
                    array(
                        'label' => 'Select Category',
                        'value' => '',
                    ),  
                ),
            ),
            'attributes' => array(
                'value' => '',
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
