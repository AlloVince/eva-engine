<?php
namespace User\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class UserFieldsForm extends UserForm
{

    protected $subFormGroups = array(
        'default' => array(
            'CommonFields' => 'User\Form\UserCommonFieldsForm',
        ),
    );

    protected $baseElements = array (
        'id' => array (
            'name' => 'id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
    );

    protected $baseFilters = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );

    public function beforeBind($data)
    {
        if(isset($data['UserRoleFields'][0]['value'])){
            $fieldvalues = array();
            foreach($data['UserRoleFields'] as $key => $fieldValue){
                $fieldvalues[$fieldValue['field_id']] = \Zend\Json\Json::decode($fieldValue['value']);
            }
            $data['UserRoleFields'] = $fieldvalues;
        }

        if(isset($data['UserCommonFields'])){
            $fieldValueArray = array();
            $fieldValues = $data['UserCommonFields'];
            foreach($fieldValues as $fieldValue){
                $fieldValueArray[$fieldValue['field_id']] = \Zend\Json\Json::decode($fieldValue['value']);
            }
            $data['CommonFields'] = $fieldValueArray;
        }
        return $data;
    }


    public function prepareData($data)
    {
        if(isset($data['UserRoleFields']) && is_array($data['UserRoleFields'])){
            $fieldvalues = array();
            foreach($data['UserRoleFields'] as $key => $fieldValue){
                if(!$fieldValue){
                    continue;
                }
                $fieldvalues[] = array(
                    'field_id' => $key,
                    'value' => \Zend\Json\Json::encode($fieldValue),
                );
            }
            $data['UserRoleFields'] = $fieldvalues;
        }

        if(isset($data['CommonFields']) && is_array($data['CommonFields'])){
            $fieldvalues = array();
            foreach($data['CommonFields'] as $key => $fieldValue){
                if(!$fieldValue){
                    continue;
                }
                $fieldvalues[] = array(
                    'field_id' => $key,
                    'value' => \Zend\Json\Json::encode($fieldValue),
                );
            }
            $data['UserCommonFields'] = $fieldvalues;
        }

        return $data;
    }
}
