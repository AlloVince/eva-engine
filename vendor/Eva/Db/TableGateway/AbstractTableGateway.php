<?php
namespace Eva\Db\TableGateway;
use Eva\Db\Adapter\Adapter,
	Eva\Db\ResultSet\ResultSet;
class AbstractTableGateway extends \Zend\Db\TableGateway\AbstractTableGateway
{
	protected $tablePrefix = 'eva';
	protected $moduleName = '';
    protected $table = '';
	protected $tableName = '';

	public function getTablePrefix()
	{
		if($this->tablePrefix) {
			return $this->tablePrefix;
		}

		return '';
	}

	public function setTablePrefix($tablePrefix)
	{
		$this->tablePrefix = $tablePrefix;
		return $this;
	}

	public function getModuleName()
	{
		$className = get_class($this);
		$moduleName = explode('\\', $className);
		$moduleName = strtolower($moduleName[0]);
		return $moduleName;
	}

	public function setModuleName($moduleName)
	{
		$this->moduleName = $moduleName;
		return $this;
	}

	/*
	public function initAdapter()
	{
		if(true === \Eva\Registry::isRegistered('dbAdapter')){
			$this->adapter = \Eva\Registry::get("dbAdapter");
			return $this->adapter;
		}
	}
	 */

	public function initTableName()
	{
		$this->table = $this->tablePrefix . '_' . $this->getModuleName() . '_' . $this->tableName;
		return $this;
	}


    public function __construct(\Zend\Db\Adapter\Adapter $adapter = null)
	{
		if($adapter) {
        	$this->adapter = $adapter;
		}

		$this->initTableName();
		$this->initialize();
    }
}
