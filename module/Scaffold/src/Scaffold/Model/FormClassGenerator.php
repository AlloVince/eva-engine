<?php
namespace Scaffold\Model;

use Eva\Form\Form;
use Zend\Form\Element;

class FormClassGenerator
{
    protected $elements;
    protected $dbTableName;
    protected $metadata;
    protected $validatorGenerator;

    public function getValidatorGenerator()
    {
        if($this->validatorGenerator){
            return $this->validatorGenerator;
        }

        return new ValidatorOptionGenerator();
    }

    public function setElements($elements)
    {
        $this->elements = $elements;
        return $this;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function setDbTableName($dbTableName)
    {
        $this->dbTableName = $dbTableName;
        return $this;
    }

    public function getDbMetadata()
    {
        if($this->metadata){
            return $this->metadata;
        }

        $adapter = \Eva\Api::_()->getDbAdapter();
        $metadata = new \Eva\Db\Metadata\Metadata($adapter);
        $columns = $metadata->getColumns($this->dbTableName);
        $props = array(
            'name', 'ordinal_position', 'column_default', 'is_nullable',
            'data_type', 'character_maximum_length', 'character_octet_length',
            'numeric_precision', 'numeric_scale', 'numeric_unsigned',
            'erratas', 'column_type'
        );
        $res = array();
        foreach ($columns as $column) {
            $columnName = $column->getName();
            foreach ($props as $prop) {
                $res[$columnName][$prop] = $column->{'get' . str_replace('_', '', $prop)}();
            }
        }    
        return $this->metadata = $res;
    }


    public function getFormNamespace()
    {
        $dbTableName = $this->dbTableName;
        $className = explode("_", $dbTableName);
        array_shift($className);
        array_pop($className);
        array_push($className, 'Form');
        $className = array_map(function($string){
            return ucfirst($string);
        }, $className);
        return implode("\\", $className);
    }

    public function getFormClassName()
    {
        $dbTableName = $this->dbTableName;
        $className = explode("_", $dbTableName);
        array_shift($className);
        array_shift($className);
        array_push($className, 'Form');
        $className = array_map(function($string){
            return ucfirst($string);
        }, $className);
        return implode("", $className);
    }

    public function printCode($array)
    {
        $varDump = var_export($array, true);
        $varDump = preg_replace('/\d+\s+\=>\s+/','',$varDump);
        $varDump = preg_replace('/=>\s+\n\s+array\s+\(/','=> array (',$varDump);
        $varDump = str_replace('  ', '    ', $varDump);
        return $varDump;
    }

    public function convertToFormArray()
    {
        $elements = $this->elements;
        $columns = $this->getDbMetadata();

        $elementsArray = array();
        $validatorsArray = array();

        foreach($elements as $key => $element){
            if($this->isMultiOptionElement($element)){
                $elementArray = $this->getMultiTypeElement($element); 
            } else {
                $elementArray = $this->getSingleTypeElement($element);
            }

            $validatorArray = array(
                'name' => $key,
                'required' => false,
                'filters' => $this->getFilterArray($element),
                'validators' => $this->getValidatorArray($element),
            );

            $elementsArray[$key] = $elementArray;
            $validatorsArray[$key] = $validatorArray;
        }

        return array($elementsArray, $validatorsArray);
    }

    protected function checkRequired($element)
    {
    }

    protected function parseEnumString($enum)
    {
        $enum = str_replace(array('enum','(', ')', '\''),'', $enum);
        $enum = explode(',', $enum);
        $enumArray = array();
        foreach($enum as $enumName){
            $enumArray[$enumName] = array(
                'label' => $this->getLabel($enumName),
                'value' => $enumName,
            );
        }

        return $enumArray;
    }

    protected function getMultiTypeElement($element)
    {
        $metadata = $this->metadata;
        $elementMeta = $metadata[$element['name']];
        $options = array();
        if($elementMeta['data_type'] == 'enum'){
            $options = $this->parseEnumString($elementMeta['column_type']);
        }
        $value = null;
        if($element['default']){
            if($this->isMultiValueElement($element)){
                $value = array($element['default']);
            } else {
                $value = $element['default'];
            }
        }
        $elementArray = array(
            'name' => $element['name'],
            'type' => $element['type'],
            'options' => array(
                'label' => $this->getLabel($element['name']),
                'value_options' => $options,
            ),
            'attributes' => array(
            ),
        );
        if($value){
            $elementArray['attributes']['value'] = $value;
        }
        return $elementArray;
    }

    protected function getSingleTypeElement($element)
    {

        $elementArray = array(
            'name' => $element['name'],
            'type' => $element['type'],
            'options' => array(
                'label' => $this->getLabel($element['name']),
            ),
            'attributes' => array(
                'value' => $element['default'],
            ),
        );

        return $elementArray;
    }

    protected function isMultiOptionElement($element)
    {
        switch($element['type']){
            case 'select':
            case 'radio':
            case 'multiCheckbox':
            return true;
            default :
            return false;
        }
    }

    protected function isMultiValueElement($element)
    {
        switch($element['type']){
            case 'multiCheckbox':
            return true;
            default :
            return false;
        }
    }

    protected function getLabel($key)
    {
        if($key){
            $key = ucfirst($key);
            return preg_replace( '/([a-z0-9])([A-Z])/', "$1 $2", $key);
        }
    }

    protected function getFilterArray($element)
    {
        if(!$element['filters']){
            return array();
        }

        $filters = array();
        foreach($element['filters'] as $filterName){
            $filters[lcfirst($filterName)] = array(
                'name' => $filterName
            );
        }

        return $filters;
    }

    protected function getValidatorArray($element)
    {
        if(!$element['validators']){
            return array();
        }
        $metadata = $this->metadata;
        $elementMeta = $metadata[$element['name']];

        return $this->getValidatorGenerator()->getValidatorOption($element, $elementMeta);
    }

}
