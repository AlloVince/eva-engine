<?php
namespace File\Form;

class FileSearchForm extends FileEditForm
{
    protected $mergeElements = array(
        'status' => array (
            'attributes' => array (
                'options' => array (
                    array (
                        'label' => 'Select Status',
                        'value' => '',
                    ),
                ),
                'value' => '',
            ),
        ),
        'keyword' =>     array(
            'name' => 'keyword',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Keyword',
            ),
        ),
        'fileSizeFrom' => array (
            'name' => 'fileSizeFrom',
            'attributes' => array (
                'type' => 'range',
                'label' => 'From',
            ),
        ),
        'fileSizeTo' => array (
            'name' => 'fileSizeTo',
            'attributes' => array (
                'type' => 'range',
                'label' => 'To',
            ),
        ),
        'imageWidthFrom' => array (
            'name' => 'imageWidthFrom',
            'attributes' => array (
                'type' => 'number',
                'label' => 'From',
            ),
        ),
        'imageWidthTo' => array (
            'name' => 'imageWidthTo',
            'attributes' => array (
                'type' => 'number',
                'label' => 'To',
            ),
        ),
        'imageHeightFrom' => array (
            'name' => 'imageHeightFrom',
            'attributes' => array (
                'type' => 'number',
                'label' => 'From',
            ),
        ),
        'imageHeightTo' => array (
            'name' => 'imageHeightTo',
            'attributes' => array (
                'type' => 'number',
                'label' => 'To',
            ),
        ),
        'isImage' => array (
            'name' => 'isImage',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'use_hidden_element' => false,
                'checked_value' => false,
            ),
            'attributes' => array (
                'type' => 'checkbox',
                'label' => 'Is Image',
                'value' => '1',
            ),
        ),
    );
}
