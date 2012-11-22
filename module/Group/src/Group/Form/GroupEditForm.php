<?php
namespace Group\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class GroupEditForm extends GroupForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Text' => 'Group\Form\TextForm',
            'GroupFile' => 'Group\Form\GroupFileForm',
        ),
    );

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'groupKey' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'table' => 'group_groups',
                        'field' => 'groupKey',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                        'messages' => array(
                            'recordFound' => 'Abc',
                        ), 
                    ),
                ),
            ),
        ),
    );


   public function prepareData($data)
    {
        if(isset($data['GroupFile'])){
            $data['GroupFile']['group_id'] = $data['id'];
        }

        return $data;
    }
}
