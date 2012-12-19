<?php
namespace Group\Form;

class CategorySearchForm extends CategoryForm
{
    protected $mergeElements = array(
        'keyword' =>     array(
            'name' => 'keyword',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Keyword',
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
    );


    public function prepareData($data)
    {
        if(!$data['page']){
            $data['page'] = 1;
        }

        return $data;
    }
}
