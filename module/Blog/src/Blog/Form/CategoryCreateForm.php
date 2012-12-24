<?php
namespace Blog\Form;

class CategoryCreateForm extends CategoryForm
{
    protected $mergeFilters = array(
        'urlName' => array (
            'required' => false,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'blog_categories',
                    ),
                ),
            ),
        ),
    );

    public function prepareData($data)
    {
        if(!$data['parentId']){
            $data['parentId'] = '0';
        }

        return $data;
    }
}
