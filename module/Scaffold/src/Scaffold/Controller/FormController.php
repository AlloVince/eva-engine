<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\Api,
    Eva\Db\Metadata\Metadata,
    Eva\View\Model\ViewModel;

class FormController extends RestfulModuleController
{
    protected $addResources = array(
        'test',
        'html'
    );
    
    protected $renders = array(
        'restPutFormHtml' => 'form/put',    
    ); 

    public function restGetFormHtml()
    {
    }

    public function restGetFormTest()
    {
    }
    public function restGetForm() 
    {
        $query = $this->getRequest()->getQuery();
        
        $tab = $this->getEvent()->getRouteMatch()->getParam('id'); 
        
        $adapter = Api::_()->getDbAdapter();
        
        $metadata = new Metadata($adapter);

        $columns = $metadata->getColumns($tab);
        
        $props = array(
            'name', 'ordinal_position', 'column_default', 'is_nullable',
            'data_type', 'character_maximum_length', 'character_octet_length',
            'numeric_precision', 'numeric_scale', 'numeric_unsigned',
            'erratas', 'column_type'
        );
        
        foreach ($columns as $column) {
            $columnName = $column->getName();
            foreach ($props as $prop) {
                $res[$columnName][$prop] = $column->{'get' . str_replace('_', '', $prop)}();
            }
        }
        return array(
            'columns' => $res,
            'table' => $tab
        );
    }
    
    public function restPutForm()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $tableName = $this->params()->fromRoute('id'); 
        
        if(!$postData['selectedColumns']){
            return false;
        }

        $elements = array();

        foreach($postData['selectedColumns'] as $columnName){
            $elements[$columnName] = array(
                'name' => $columnName,
                'type' => @$postData['inputType'][$columnName],
                'validators' => @$postData['validators'][$columnName],
                'filters' => @$postData['filters'][$columnName],
                'default' => @$postData['defaults'][$columnName],
            );
        }

        $generator = new \Scaffold\Model\FormClassGenerator();
        $generator->setElements($elements);
        $generator->setDbTableName($tableName);
        list($elements, $validators) = $generator->convertToFormArray();

        $elementsCode = $generator->printCode($elements);
        $validatorsCode = $generator->printCode($validators);
        $formClassName = $generator->getFormClassName();
        $formNamespace = $generator->getFormNamespace();
        return array(
            'formNamespace' => $formNamespace,
            'formClassName' => $formClassName,
            'elementsCode' => $elementsCode,
            'validatorsCode' => $validatorsCode,
        );
    }
    
    public function restPutFormHtml()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
            
        $mainForm = $postData->form;

        $form = Api::_()->getForm($mainForm);
        $elements = $form->mergeElements();

        $subFormString = $postData->subform;

        $subFormElements = array();

        if ($subFormString) {
            $subForms = explode(',', $subFormString);
            foreach ($subForms as $subForm) {
                $form = Api::_()->getForm($subForm);
                $subFormElements[$subForm] = $form->mergeElements();
            }
        }
        
        $generator = new \Scaffold\Model\FormHtmlGenerator();
        $generator->setElements($elements);
        $subFormElements ? $generator->setSubFormElements($subFormElements) : null;
        $generator->setFormClassName($mainForm);
        list($elements, $subForms) = $generator->convertToFormHtml();

        $subFormsCode = $generator->printCode($subForms);
        $formClassName = $generator->getFormClassName();
   
        return array(
            'formClassName' => $formClassName,
            'elements' => $elements,
            'subFormsCode' => $subFormsCode,
        );
    }

}
