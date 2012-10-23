<?php
namespace Video\Form;

class MessageSearchForm extends MessageForm
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
    );


    public function prepareData($data)
    {
        if(!$data['page']){
            $data['page'] = 1;
        }

        return $data;
    }
}
