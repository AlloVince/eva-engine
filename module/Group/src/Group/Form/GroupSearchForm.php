<?php
namespace Group\Form;

class GroupSearchForm extends GroupForm
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
                'label' => 'Creator Id',
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
                'empty_option' => 'Group Status',
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'category' =>     array(
            'name' => 'category',
            'type' => 'text',
            'options' => array(
                'label' => 'Category',
            ),
            'attributes' => array(
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
