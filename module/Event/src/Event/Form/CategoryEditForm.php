<?php
namespace Event\Form;

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
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => 100,
                    ),
                ),
                array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'event_categories',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                    ),
                ),
            ),
        ),
    );
}
