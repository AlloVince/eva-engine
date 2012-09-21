<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\Api,
    Eva\Db\Metadata\Metadata,
    Eva\View\Model\ViewModel;

class FieldController extends RestfulModuleController
{
    protected $columns;
    
    public function restIndexField()
    {
        $use = 'Epic\Form\\';

        $roles = array(
            'Connoisseur',
            'Corporate',
            'Professional',
        );
    
        foreach ($roles as $role) {
            $form = $use . $role . 'Form';
            $this->makeFiled($form, $role);
        }
    
        exit;
    }

    public function makeFiled($formName,$role)
    {
        if (!$formName) {
            return false;
        }   
    
        $form = Api::_()->getForm($formName);
        $elements = $form->mergeElements();
        
        if ($elements) {
            foreach ($elements as $element) {
                $this->insert($element, $role);
            }
        } 
        
        $use = 'Epic\Form\\';
        
        $subform = Api::_()->getForm($use . $role . 'ProfileForm');
        $elements = $subform->mergeElements();

        if ($elements) {
            foreach ($elements as $element) {
                $this->insert($element, $role);
            }
        }
    }

    public function insert($element, $role)
    {
        if ($this->check($element['name']) == true) {
            return false;
        }
        
        if ($element['type'] == 'hidden') {
            return false;
        }
        
        $roles = array(
            'Corporate' => 1,
            'Connoisseur' => 2,
            'Professional'=> 3,
        );
        
        $field_role = Api::_()->getDbTable('\User\DbTable\FieldsRoles');
        $field = Api::_()->getDbTable('\User\DbTable\Fields');
        $fieldoption = Api::_()->getDbTable('\User\DbTable\Fieldoptions');

        $fieldInfo = array(
            'fieldName' => $element['options']['label'] . "(" . $role .  ")",
            'fieldKey' => $element['name'],
            'fieldType' => $element['type'],
            'label' => $element['options']['label'],
            'display' => 1,
        ); 
        
        $field->create($fieldInfo); 
        $field_id = $field->getLastInsertValue(); 
        
        $field_role->create(array(
            'field_id' => $field_id,
            'role_id' => $roles[$role],
        ));
    
        if ($element['type'] != "text" && $element['options']['value_options']) {
            foreach ($element['options']['value_options'] as $option) {
                $fieldoption->create(array(
                    'field_id' => $field_id,
                    'label' => $option['label'],
                    'option' => $option['value'],
                )); 
            }
        }
    }

    public function check($elementName)
    {
        if ($this->columns) {
            $res = $this->columns;   
        } else {

            $tabs = array("eva_user_users", "eva_user_profiles");

            $adapter = Api::_()->getDbAdapter();

            $metadata = new Metadata($adapter);

            foreach ($tabs as $tab) {

                $columns = $metadata->getColumns($tab);

                foreach ($columns as $column) {
                    $columnName = $column->getName();
                    $res[$columnName] = $columnName;
                } 
            }

            $this->columns = $res;
        }

        return empty($res[$elementName]) ? false : true;
    }
}
