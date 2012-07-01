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

    protected $defaultValues;

    protected $formMethod;

    protected $restfulMethod;

    protected $idPrefix;

    protected $hasIdPrefix = true;

    protected $fieldsMap = array();

    protected $subForms = array();

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
        $subFormClass = $subFormConfig[0];
        $subForm = new $subFormClass;
        $this->subElements[$formName] = $subElements = $subForm->getMergedElements();
        $this->subFilters[$formName] = $subFilters = $subForm->getMergedFilters();

        $fieldset = new \Zend\Form\Fieldset($formName);
        $factory = $this->getFormFactory();
        foreach($subElements as $subElementKey => $subElement){
            $subElementKey = $formName . '[' . $subElementKey . ']';
            $subElement['name'] = $subElementKey;
            $fieldset->add($factory->create($subElement));
        }
        $this->add($fieldset);


        $this->baseFilters = array_merge($this->baseFilters, 
            array(
                $formName => array_merge( array('type' => 'Zend\InputFilter\InputFilter'), $subFilters)
            )
        );

        //p($this->baseFilters);


        /*
        $newSubElements = array();
        foreach($subElements as $subElementKey => $subElement){
            $subElementKey = $formName . '[' . $subElementKey . ']';
            $subElement['name'] = $subElementKey;
            $newSubElements[$subElementKey] = $subElement;
        }
        unset($subElements);

        $newSubFilters = array();
        foreach($subFilters as $filterKey => $subFilter){
            $subFilterKey = $formName . '[' . $filterKey . ']';
            $subFilter['name'] = $subFilterKey;
            $newSubFilters[$subFilterKey] = $subFilter;
        }
        unset($subFilters);

        $this->baseElements = array_merge($this->baseElements, $newSubElements);
        $this->baseFilters = array_merge($this->baseFilters, $newSubFilters);
        */
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

    public function setDefaultValues($defaultValues)
    {
        $this->defaultValues = $defaultValues;
        return $this;
    }

    public function getDefaultValues()
    {
        return $this->defaultValues;
    }

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
        $values = $options['values'];

        $elements = array_merge($this->baseElements, $this->mergeElements);
    
        if(is_object($values)){
            foreach($elements as $name => $element){
                if(isset($values->$name)){
                    $element['attributes']['value'] = $values->$name;
                }
                $this->add($element);
            }
        } else {
            foreach($elements as $name => $element){
                if(isset($values[$name])){
                    $element['attributes']['value'] = $values[$name];
                }
                $this->add($element);
            }
        }
        return $this;
    }

    public function enableFilters(array $filterOptions = array())
    {
        $inputFilter = new InputFilter;

        $filters = array_merge($this->baseFilters, $this->mergeFilters, $filterOptions);


        if(!$filters){
            $this->setInputFilter($inputFilter);
            return $this;
        }
        
        $factory = new FilterFactory();

        $inputFilter = $factory->createInputFilter($filters);
        $this->setInputFilter($inputFilter);

        //$this->attachInputFilterDefaults($inputFilter, $this);
        return $this;
    }

    /*
    public function input($elementOrName, $options)
    {
        $element = $elementOrName instanceof \Zend\Form\ElementInterface ? $element : null;
        if(!$element && is_string($elementOrName)){
            $element = $this->get($elementOrName);
        }

        if(!$element){
            throw new Exception\UnexpectedMethodException(sprintf(
                'Request element %s not found in form',
                __METHOD__,
                $elementOrName
            ));
        }

        if($options){
            $element = clone $element;
            foreach($options as $key => $value){
                $element->setAttribute($key, $value);
            }
        }

        //$view = \Eva\Api::_()->getView();
        //return $view->$elementType($element);
    }
     */

    public function mergeInvalid()
    {
        /*
        $inputFilter = $this->getInputFilter();
        if(!$inputFilter) {
            return $this;
        }

        $invalids = $inputFilter->getInvalidInput();
        $elements = $this->getElements();

        foreach($invalids as $key => $invalid){
            if(isset($elements[$key])){
                $elements[$key]->setMessages($invalid->getMessages());
            }
        }
        */
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

    /*
    public function populateValues($data)
    {
        if (!is_array($data) && !$data instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
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
            if ($element instanceof FieldsetInterface && is_array($value)) {
                $element->populateValues($value);
                continue;
            }

            $element->setAttribute('value', $value);
        }
    }
    */


    /*
    public static function _()
    {
        return self::getInstance();
    }

    public static function getInstance()
    {
        if( is_null(self::$instance) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
     */
}
