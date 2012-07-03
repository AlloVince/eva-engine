<?php
namespace Eva\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as FilterFactory;

class Form extends \Zend\Form\Form
{
    protected static $instance;

    protected $baseElements = array();
    protected $mergeElements = array();
    protected $subElements = array();
    
    protected $baseFilters = array();
    protected $mergeFilters = array();
    protected $subFilters = array();

    //protected $defaultValues;

    protected $formMethod;

    protected $restfulMethod;

    protected $autoElementId = true;
    protected $idPrefix;

    protected $fieldsMap = array();

    protected $subForms = array();

    protected $elementInited = false;
    protected $subFormInited = array();
    //protected $valuesInited = false;

    public function setAutoElementId($autoElementId)
    {
        $this->autoElementId = (boolean) $autoElementId;
        return $this;
    }

    public function setIdPrefix($idPrefix)
    {
        $this->idPrefix = (string) $idPrefix;
        return $this;
    }

    public function getIdPrefix()
    {
        if($this->idPrefix){
            return $this->idPrefix;
        }

        return $this->idPrefix = get_class($this);
    }

    public function getMergedElements()
    {
        return array_merge($this->baseElements, $this->mergeElements);
    }

    public function getMergedFilters()
    {
        return array_merge($this->baseFilters, $this->mergeFilters);
    }

    public function setSubForms(array $subForms = array())
    {
        foreach($subForms as $formName => $subForm){
            $this->addSubForm($formName, $subForm);
        }
        return $this;
    }

    public function subForm($subFormName)
    {
        return $this->get($subFormName);
    }

    protected function addSubForm($formName, array $subFormConfig = array()) 
    {
        //Sub form will be allow init once
        if(isset($this->subFormInited[$formName]) && true === $this->subFormInited[$formName]){
            return $this;
        }

        $subFormClass = $subFormConfig[0];
        $subForm = new $subFormClass;
        $this->subElements[$formName] = $subElements = $subForm->getMergedElements();
        $this->subFilters[$formName] = $subFilters = $subForm->getMergedFilters();

        $fieldset = new \Zend\Form\Fieldset($formName);
        $factory = $this->getFormFactory();
        foreach($subElements as $subElementKey => $subElement){
            $subElement['attributes']['data-subform-name'] = $formName;
            $subElement = $this->autoElementId($subElement, $subFormClass);
            $fieldset->add($factory->create($subElement));
        }
        $this->add($fieldset);


        $this->baseFilters = array_merge($this->baseFilters, 
            array(
                $formName => array_merge( array('type' => 'Zend\InputFilter\InputFilter'), $subFilters)
            )
        );

        $this->subFormInited[$formName] = true;
        return $this; 
    }

    //TODO: $form->get('title') when title is null should throw a new Exception 
    public function fieldsMap($data = array(), $quickMode = false, $skipFieldStart = '_')
    {
        if(is_object($data)){
            $data = $data->toArray();
        }

        if(!$data || !$this->fieldsMap && $quickMode === false){
            return $data;
        }

        if(true === $quickMode){
            foreach($data as $key => $value){
                if(false === strpos($key, $skipFieldStart)){
                    continue;
                }

                unset($data[$key]);
            }
        } else {

            $fieldsMap = $this->fieldsMap;
            $newData = array();
            foreach($data as $key => $value){
                if(isset($fieldsMap[$key])){
                    $newData[$fieldsMap[$key]] = $value;
                }
            }
            $data = $newData;
            unset($newData);
        }


        return $data;
    }

    /*
    public function setDefaultValues($defaultValues)
    {
        $this->defaultValues = $defaultValues;
        return $this;
    }

    public function getDefaultValues()
    {
        return $this->defaultValues;
    }
    */

    public function init(array $options = array())
    {
        $defaultOptions = array(
            'action' => '',
            'values' => array(),
            'method' => 'get',    
        );

        $options = array_merge($defaultOptions, $options);

        $method = $options['method'];
        if($method){
            $this->setMethod($method);
        }

        $action = $options['action'];
        if($action){
            $this->setAttribute('action', $action);
        }

        if(false === $this->elementInited){
            $elements = array_merge($this->baseElements, $this->mergeElements);

            foreach($elements as $name => $element){
                $element = $this->autoElementId($element);
                $this->add($element);
            }
            $this->elementInited = true;
        }


        $values = $options['values'];
        if($values){
            if(is_object($values)){
                $this->bind($values);
            } else {
                $this->bind(new \ArrayObject($values));
            }
            $this->bindValues();
        }

        return $this;
    }

    public function enableFilters(array $filterOptions = array())
    {

        $filters = array_merge($this->baseFilters, $this->mergeFilters, $filterOptions);

        if(!$filters){
            $inputFilter = new InputFilter;
            $this->setInputFilter($inputFilter);
            return $this;
        }
        
        $factory = new FilterFactory();

        $inputFilter = $factory->createInputFilter($filters);
        $this->setInputFilter($inputFilter);
        return $this;
    }

    public function setMethod($method = '')
    {
        if(!$method){
            return $this;
        }

        $allowMethods = array('get', 'put', 'post', 'delete');
        $method = strtolower($method);

        if(false === in_array($method, $allowMethods)){
            throw new Exception\UnexpectedMethodException(sprintf(
                'Input Method %s is not correct http method',
                __METHOD__,
                $method
            ));
        }


        $restfulMethod = 'get';
        switch($method){
            case 'post' :
                $restfulMethod = 'post';
                break;
            case 'get' :
                break;
            case 'put' :
                $restfulMethod = 'put';
                $method = 'post';
                break;
            case 'delete' :
                $restfulMethod = 'delete';
                $method = 'post';
                break;
            default:
        }

        $this->setAttribute('method', $method);
        $this->restfulMethod = $restfulMethod;

        return $this;
    }

    public function restful()
    {
        //TODO: restful input name should be able to custom
        return sprintf('<input type="hidden" name="_method" value="%s">', $this->restfulMethod);
    }

    public function populateValues($data)
    {
        if (!is_array($data) && !$data instanceof Traversable) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable set of data; received "%s"',
                __METHOD__,
                (is_object($data) ? get_class($data) : gettype($data))
            ));
        }

        foreach ($data as $name => $value) {
            if (!$this->has($name)) {
                continue;
            }

            $element = $this->get($name);
            //Fixed if bind value is ArrayObject;
            if ($element instanceof \Zend\Form\FieldsetInterface && (is_array($value) || $value instanceof \ArrayObject)) {
                $element->populateValues((array) $value);
                continue;
            }

            $element->setAttribute('value', $value);
        }
    }

    public function getElement($elementNameOrArray)
    {
        if(true === is_string($elementNameOrArray)){
            return $this->get($elementNameOrArray);
        }

        if(true === is_array($elementNameOrArray) && isset($elementNameOrArray[0]) && isset($elementNameOrArray[1])){
            $fieldset = $this->get($elementNameOrArray[0]);
            return $fieldset->get($elementNameOrArray[1]);
        }

        throw new Exception\UnexpectedElementException(sprintf(
            '%s Request element %s not correct',
            __METHOD__,
            $elementNameOrArray
        ));
    }

    public function autoElementId(array $element, $idPrefix = null)
    {
        if(!$this->autoElementId){
            return $element;
        }

        $idPrefix = $idPrefix ? $idPrefix : $this->getIdPrefix();
        $elementId = isset($element['attributes']['id']) ? $element['attributes']['id'] : $element['name'];
        $elementId = $idPrefix . '-' . $elementId;
        $elementId = str_replace(array('\\','_','[',']'), '-', strtolower($elementId));
        $elementId = trim($elementId, '-');
        $element['attributes']['id'] = $elementId;
        return $element;
    }

    public function helper($elementName, $optionOrInputType = null, array $options = array())
    {
        $view = \Eva\Api::_()->getView();

        $element = $this->getElement($elementName);

        if($optionOrInputType){
            if(is_string($optionOrInputType)){
                $options = array_merge(array('type' => $optionOrInputType), $options);
            } else {
                $options = array_merge($optionOrInputType, $options);
            }
        }
        return $view->input($element, $options); 
    }

    public function isError($elementName)
    {
        $element = $this->getElement($elementName);
        return $element->getMessages() ? true : false;
    }

}
