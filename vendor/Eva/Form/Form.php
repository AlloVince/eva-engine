<?php
namespace Eva\Form;

class Form extends \Zend\Form\Form
{
	protected static $instance;

	protected $baseElements;

	protected $mergeElements;

	protected $baseFilters;

	protected $mergeFilters;

	protected $defaultValues;

	public function setDefaultValues($defaultValues)
	{
		$this->defaultValues = $defaultValues;
		return $this;
	}

	public function getDefaultValues()
	{
		return $this->defaultValues;
	}

	public function initBaseForm()
	{
		$elements = $this->baseElements;
		foreach($elements as $name => $element){
			$this->add($element);
		}
		return $this;
	}

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

	public function attr()
	{
	}

	public function restfulMethod($method = 'get')
	{
	}
}
