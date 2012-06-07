<?php
namespace Eva\Db\TableGateway;
use Eva\Api,
	Zend\Db\Adapter\Adapter,
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

	public function initTableName()
	{
		$this->table = $this->getTablePrefix() . $this->getModuleTableName() . '_' . $this->tableName;
		return $this;
	}

	public function fieldsMapping()
	{
	}

	public function where()
	{
	}

	public function find()
	{
	}

	public function setField()
	{
	}

	public function save()
	{
	}

	public function create()
	{
	}

	public function switchAdapter()
	{
	
	}

	public function getCount()
	{
	}

	public function getPager()
	{
	
	}

    public function __construct(Adapter $adapter = null)
	{
		if($adapter) {
        	$this->adapter = $adapter;
		}

		$this->initTableName();
		$this->initialize();
    }
}
