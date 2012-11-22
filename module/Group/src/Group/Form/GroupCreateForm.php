<?php
namespace Group\Form;

class GroupCreateForm extends GroupForm
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
        'groupName' => array(
            'required' => true,
        ),
        'groupKey' => array (
            'required' => false,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'groupKey',
                        'table' => 'group_groups',
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
