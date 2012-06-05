<?php
namespace Eva;
class Api
{
	protected static $instance;
	protected $event;
	protected $config;
	protected $dbAdapter;

	public function setEvent($event)
	{
		$this->event = $event;
	}

	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * Shorthand for getInstance
	 *
	 * @return Engine_Api
	 */
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


	/**
	 * Set or unset the current api instance
	 * 
	 * @param Engine_Api $api
	 * @return Engine_Api
	 */
	public static function setInstance(Api $api = null) 
	{
		return self::$instance = $api;
	}

	public function getConfig()
	{
		$event = $this->getEvent();
		$app = $event->getParam('application');
		return $app->getConfiguration();
	}

	public function setConfig($config)
	{
	}

	public function setDbAdapter($dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		return self::$instance;
	}

	public function getDbAdapter()
	{
		if($this->dbAdapter) {
			return $this->dbAdapter;
		}

		$config = $this->getConfig();
		$dbAdapter = new \Zend\Db\Adapter\Adapter($config['db']);
		return $this->dbAdapter = $dbAdapter;
	}

	public function getDbTable($tableClassName)
	{
		return new $tableClassName($this->getDbAdapter());
	}

	public function getForm($formClassName)
	{
		return new $formClassName;
	}
}
