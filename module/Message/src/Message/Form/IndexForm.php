<?php
namespace Message\Form;

class IndexForm extends \Eva\Form\Form
{
    protected $mergeElements = array(
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
        'author_id' =>     array(
            'name' => 'author_id',
            'type' => 'hidden',
            'options' => array(
                'label' => 'author_id',
            ),
            'attributes' => array(
            ),
        ),
    );

    protected $mergeFilters = array(
    );

    public function prepareData($data)
    {
        if(!$data['page']){
            $data['page'] = 1;
        }

        if(!$data['order']) {
            $data['order'] = 'timedesc';
        }
        
        if(!$data['author_id']) {
            $user = \Core\Auth::getLoginUser();
            $data['author_id'] = $user['id'];
        }

        return $data;
    }
}
