<?php
namespace File\Form;

class FileSearchForm extends FileForm
{
    protected $mergeElements = array(
        'status' => array (
            'options' => array (
                'empty_option' => 'Select Status',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'keyword' =>     array(
            'name' => 'keyword',
            'type' => 'text',
            'options' => array (
                'label' => 'Keyword',
            ),
            'attributes' => array(
            ),
        ),
        'fileSizeFrom' => array (
            'name' => 'fileSizeFrom',
            'type' => 'range',
            'options' => array (
                'label' => 'From',
            ),
            'attributes' => array (
            ),
        ),
        'fileSizeTo' => array (
            'name' => 'fileSizeTo',
            'type' => 'range',
            'options' => array (
                'label' => 'To',
            ),
            'attributes' => array (
            ),
        ),
        'imageWidthFrom' => array (
            'name' => 'imageWidthFrom',
            'type' => 'number',
            'options' => array (
                'label' => 'From',
            ),
        ),
        'imageWidthTo' => array (
            'name' => 'imageWidthTo',
            'type' => 'number',
            'options' => array (
                'label' => 'To',
            ),
        ),
        'imageHeightFrom' => array (
            'name' => 'imageHeightFrom',
            'type' => 'number',
            'options' => array (
                'label' => 'From',
            ),
        ),
        'imageHeightTo' => array (
            'name' => 'imageHeightTo',
            'type' => 'number',
            'options' => array (
                'label' => 'To',
            ),
        ),
        'isImage' => array (
            'name' => 'isImage',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Is Image',
                'use_hidden_element' => false,
                'checked_value' => '1',
            ),
            'attributes' => array (
                'value' => '1',
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

    protected $mergeFilters = array(
        'fileSizeFrom' => array (
            'name' => 'fileSizeFrom',
            'required' => false,
        ),
        'fileSizeTo' => array (
            'name' => 'fileSizeTo',
            'required' => false,
        ),
        'imageWidthFrom' => array (
            'name' => 'imageWidthFrom',
            'required' => false,
        ),
        'imageWidthTo' => array (
            'name' => 'imageWidthTo',
            'required' => false,
        ),
        'imageHeightFrom' => array (
            'name' => 'imageHeightFrom',
            'required' => false,
        ),
        'imageHeightTo' => array (
            'name' => 'imageHeightTo',
            'required' => false,
        ),
        'isImage' => array (
            'name' => 'isImage',
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
