<?php
namespace Eva\Db\TableGateway;
use Eva\Api,
	Eva\Db\Adapter\Adapter,
	Eva\Db\ResultSet\ResultSet;
class TableGateway extends \Zend\Db\TableGateway\AbstractTableGateway
{
	protected $tablePrefix;
	protected $moduleTableName;
    protected $table;
	protected $tableName;
	protected $primaryKey = 'id';

	public function getTablePrefix()
	{
		if($this->tablePrefix) {
			return $this->tablePrefix;
		}

		$config = Api::_()->getConfig();
		if(isset($config['db']['prefix']) && $config['db']['prefix']){
			return $this->tablePrefix = $config['db']['prefix'];
		}
		return '';
	}

	public function setTablePrefix($tablePrefix)
	{
		$this->tablePrefix = $tablePrefix;
		return $this;
	}

	public function getModuleTableName()
	{
		if($this->moduleTableName){
			return $this->moduleTableName;
		}

		$className = get_class($this);
		$className = ltrim($className, '\\');
		$moduleName = explode('\\', $className);
		$moduleName = strtolower($moduleName[0]);
		return $this->moduleTableName = $moduleName;
	}

	public function setModuleTableName($moduleName)
	{
		$this->moduleTableName = $moduleName;
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
		$this->table = $this->getTablePrefix() . $this->getModuleTableName() . '_' . $this->tableName;
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
