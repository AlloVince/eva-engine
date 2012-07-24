<?php
namespace Scaffold\Model;

class ValidatorOptionGenerator
{
    protected $element;
    protected $elementMeta;


    protected function parseEnumString($enum)
    {
        $enum = str_replace(array('enum','(', ')', '\''),'', $enum);
        $enum = explode(',', $enum);
        return $enum;
    }

    protected function getValidatorInArrayOption()
    {
        $element = $this->element;
        $elementMeta = $this->elementMeta;

        $options = array();
        if($elementMeta['data_type'] == 'enum'){
            $options = $this->parseEnumString($elementMeta['column_type']);
        }

        return array(
            'haystack' => $options,
        );
    }

    protected function getValidatorStringLengthOption()
    {
        $element = $this->element;
        $elementMeta = $this->elementMeta;
        return array(
            'max' => $elementMeta['character_maximum_length']
        );
    }



    public function getValidatorOption($element, $elementMeta)
    {
        $this->element = $element;
        $this->elementMeta = $elementMeta;

        $validators = array();
        foreach($element['validators'] as $validatorName){
            $method = 'getValidator' . $validatorName . 'Option';
            $option = method_exists($this, $method) ? $this->$method() : array();
            $validators[] = array(
                'name' => $validatorName,
                'options' => $option,
            );
        } 

        return $validators;
    }

}
