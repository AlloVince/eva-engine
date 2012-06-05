<?php
namespace Eva\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class Form extends \Zend\Form\Form
{
	protected static $instance;

	protected $baseElements = array();

	protected $mergeElements = array();
	
	protected $baseFilters = array();

	protected $mergeFilters = array();

	protected $defaultValues;

	protected $formMethod;

	protected $restfulMethod;

	protected $idPrefix;

	protected $hasIdPrefix = true;

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
		
		$factory = new InputFactory();

		foreach($filters as $filter) {
			$inputFilter->add($factory->createInput($filter));
		}

		$this->setInputFilter($inputFilter);
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
		return sprintf('<input type="hidden" name="_method" value="%s">', $this->restfulMethod);
	}


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
