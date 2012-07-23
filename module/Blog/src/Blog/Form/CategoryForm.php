<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class CategoryForm extends Form
{
    protected $baseElements = array (
        'id' => 
        array (
            'name' => 'id',
            'attributes' => 
            array (
                'type' => 'hidden',
                'label' => 'Id',
            ),
        ),
        'categoryName' => 
        array (
            'name' => 'categoryName',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'CategoryName',
            ),
        ),
        'urlName' => 
        array (
            'name' => 'urlName',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'UrlName',
            ),
        ),
        'description' => 
        array (
            'name' => 'description',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'Description',
            ),
        ),
        'parentId' => 
        array (
            'name' => 'parentId',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'ParentId',
            ),
        ),
        'rootId' => 
        array (
            'name' => 'rootId',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'RootId',
            ),
        ),
        'orderNumber' => 
        array (
            'name' => 'orderNumber',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'OrderNumber',
            ),
        ),
        'createTime' => 
        array (
            'name' => 'createTime',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'CreateTime',
            ),
        ),
        'count' => 
        array (
            'name' => 'count',
            'attributes' => 
            array (
                'type' => 'text',
                'label' => 'Count',
            ),
        ),
    );

    protected $baseFilters = array (
        'id' => 
        array (
            'name' => 'id',
            'required' => false,
            'filters' => 
            array (
                array('name' => 'Int'),
            ),
            'validators' => 
            array (
            ),
        ),
        'categoryName' => 
        array (
            'name' => 'categoryName',
            'required' => true,
            'filters' => 
            array (
            ),
            'validators' => 
            array (
            ),
        ),
        'urlName' => 
        array (
            'name' => 'urlName',
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => 100,
                    ),
                ),
                array(
                    'name' => 'DbNoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'eva_blog_categories',
                        'messages' => array(
                             \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Abc',
                        ), 
                    ),

                ),
            ),
        ),
        'description' => 
        array (
            'name' => 'description',
            'required' => false,
            'filters' => 
            array (
            ),
            'validators' => 
            array (
            ),
        ),
        'parentId' => 
        array (
            'name' => 'parentId',
            'required' => false,
            'filters' => 
            array (
            ),
            'validators' => 
            array (
            ),
        ),
        'rootId' => 
        array (
            'name' => 'rootId',
            'required' => false,
            'filters' => 
            array (
            ),
            'validators' => 
            array (
            ),
        ),
        'orderNumber' => 
        array (
            'name' => 'orderNumber',
            'required' => false,
            'filters' => 
            array (
            ),
            'validators' => 
            array (
            ),
        ),
        'createTime' => 
        array (
            'name' => 'createTime',
            'required' => false,
            'filters' => 
            array (
            ),
            'validators' => 
            array (
            ),
        ),
        'count' => 
        array (
            'name' => 'count',
            'required' => false,
            'filters' => 
            array (
            ),
            'validators' => 
            array (
            ),
        ),
    );

}          
