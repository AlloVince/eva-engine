<?php
namespace Scaffold\Model;

use Eva\Form\Form;
use Zend\Form\Element;

class FormHtmlGenerator
{
    protected $elements;
    protected $subFormElements;
    protected $formClassName;

    public function setElements($elements)
    {
        $this->elements = $elements;
        return $this;
    }

    public function getElements()
    {
        return $this->elements;
    }
    
    public function setSubFormElements($subFormElements)
    {
        $this->subFormElements = $subFormElements;
        return $this;
    }

    public function getSubFormElements()
    {
        return $this->subFormElements;
    }

    public function setFormClassName($formClassName)
    {
        $this->formClassName = $formClassName;
        return $this;
    }
    
    public function getFormClassName()
    {
        return $this->formClassName;
    }

    public function getFormNameFromClassName($formClassName)
    {
        $classNameArray = explode("\\", $formClassName);
        
        return str_replace('Form', '', $classNameArray[count($classNameArray) - 1]);
    }
    
    public function printHtml($array)
    {
        return implode("\n", $array);
    }

    public function printCode($array)
    {
        $varDump = var_export($array, true);
        $varDump = preg_replace('/\d+\s+\=>\s+/','',$varDump);
        $varDump = preg_replace('/=>\s+\n\s+array\s+\(/','=> array (',$varDump);
        $varDump = str_replace('  ', '    ', $varDump);
        $varDump = str_replace('\\\\', '\\', $varDump);
        return $varDump;
    }

    public function convertToFormHtml()
    {
        $elements = $this->elements;
        $subFormElements = $this->subFormElements;

        $elementsArray = array();
        $subForms = array();
        
        if ($subFormElements) {
            foreach ($subFormElements as $subFormName=>$subElements) {
                if (!$subElements) {
                    continue;
                }
                 
                $subForms[$this->getFormNameFromClassName($subFormName)] = array($subFormName);
                
                foreach ($subElements as $subElement) {
                    $subElement['isSub'] = true;
                    $subElement['subFormName'] = $this->getFormNameFromClassName($subFormName);;
                    $elements[] = $subElement;
                }
            }
        }
        
        foreach($elements as $key => $element){
            if($this->isMultiOptionElement($element)){
                $elementsArray[$key] = $this->getMultiTypeElementHtml($element); 
            } else {
                $elementsArray[$key] = $this->getSingleTypeElementHtml($element);
            }
        
            if ($element['attributes']['type'] !== 'hidden') {
                $elementsArray[$key]['label'] = true;
            }
        }
        
        return array($elementsArray, $subForms);
    }
    
    protected function getMultiTypeElementHtml($element)
    {
        if (!$element) {
            return $element;
        }   

        $elementArray = array();

        $elementArray['name'] = $this->getElementHtmlName($element);
        $elementArray['formType'] = $this->getElementHtmlType($element);
        $elementArray['class'] = $this->getElementHtmlClass($element);
    
        return $elementArray;
    }

    protected function getSingleTypeElementHtml($element)
    {

        if (!$element) {
            return $element;
        }   
        
        if ($element['attributes']['type'] == 'textarea') {
            return $this->getMultiTypeElementHtml($element);
        }
        
        $elementArray = array();

        $elementArray['name'] = $this->getElementHtmlName($element);
        $elementArray['class'] = $this->getElementHtmlClass($element);
    
        return $elementArray;
    }

    protected function isMultiOptionElement($element)
    {
        switch($element['attributes']['type']){
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
        switch($element['attributes']['type']){
            case 'multiCheckbox':
            return true;
            default :
            return false;
        }
    }

    protected function getElementHtmlName($element)
    {
        return @$element['isSub'] == true ? "array('{$element['subFormName']}', '{$element['name']}')" : "'{$element['name']}'"; 
    }

    protected function getElementHtmlType($element)
    {
        return "'form" . ucfirst($element['attributes']['type']) . "'";
    }
    
    protected function getElementHtmlClass($element)
    {
        return "array('class' => '')";
    }
}
