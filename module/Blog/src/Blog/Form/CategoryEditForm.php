<?php
namespace Blog\Form;

class CategoryEditForm extends CategoryForm
{
    protected $mergeFilters = array(
        'urlName' =>     array(
            'name' => 'urlName',
            'required' => false,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                'stringLength' => array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => 100,
                    ),
                ),
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'blog_categories',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                    ),
                ),
            ),
        ),
    );
}
