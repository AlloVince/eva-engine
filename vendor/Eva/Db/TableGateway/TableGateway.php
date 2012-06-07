<?php
namespace Eva\Db\TableGateway;
use Eva\Api,
	Zend\Db\Adapter\Adapter,
	Eva\Db\ResultSet\ResultSet,
	Eva\Db\Exception;
class TableGateway extends \Zend\Db\TableGateway\AbstractTableGateway
{
	protected $tablePrefix;
	protected $moduleTableName;
    protected $table;
	protected $tableName;
	protected $primaryKey = 'id';

	protected $paginatorEnable = false;
	protected $paginatorOptions;
	protected $paginator;

	protected $resultSetCount;

	protected $select;
	protected $selectOptions;
	protected $lastSelectString;

	public function getSelect()
	{
		return $this->select ? $this->select : $this->sql->select();
	}

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

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}



	public function reset()
	{
		$this->select = null;
		$this->selectOptions = array(); 
		return $this;
	}

    public function __call($method, $arguments)
	{
		//Magic call below method in Db\Sql\Select
		$allowMagicCalls = array('where', 'from', 'columns', 'join', 'group', 'having', 'order', 'limit', 'offset');

		if(true === in_array($method, $allowMagicCalls)){
			if(!$this->isInitialized) {
				throw new Exception\NotInitializedException(sprintf(
					'Sql must initialized before methed %s called',
					__METHOD__,
					$method
				));
			}

			$argumentsCount = count($arguments);
			$select = $this->getSelect();
			switch($argumentsCount){
				case 1 : 
					$select->$method($arguments[0]);
					$this->selectOptions[$method] = $arguments[0];
					break;
				case 2 : 
					$select->$method($arguments[0], $arguments[1]);
					$this->selectOptions[$method] = $arguments;
					break;
				default :
					$select->$method();
					$this->selectOptions[$method] = true;
			}

			//Note: ZF2 will clear last select when use $this->sql->select();
			$this->select = $select;
			return $this;

		} else {
			return parent::__call($method, $arguments);
		}
    }

	public function count()
	{
	}

	public function page($page = 1)
	{
		$select = $this->getSelect();
		$selectOptions = $this->selectOptions;
		if(isset($selectOptions['limit']) && $selectOptions['limit']) {
			$limit = $selectOptions['limit'];
		} else {
			$limit = 10;
		}
		$offset = ($page - 1) * $limit;
		$select->offset($offset);
		return $this;
	}

	public function find($findCondition = null)
	{
		$select = $this->getSelect();
		$resultSet = $this->selectWith($select);

		$this->lastSelectString = $select->getSqlString();
		$this->reset();


		if(!$resultSet){
			return array();
		}

		return $resultSet;
	}

	public function debug()
	{
		if($this->lastSelectString){
			return $this->lastSelectString;
		}
		
		$select = $this->getSelect();
		if($select) {
			return $select->getSqlString();
		}

		return '';
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


	public function getCount()
	{
	}


	public function changeAdapter($adapterArrayOrObject)
	{
	}

	public function enablePaginator()
	{
		$this->paginatorEnable = true;
		return $this;
	}

	public function disablePaginator()
	{
		$this->paginatorEnable = false;
		return $this;
	}

	public function setPaginatorOptions($paginatorOptions)
	{
		$this->paginatorOptions = $paginatorOptions;
		return $this;
	}

	public function getPaginatorOptions()
	{
		if($this->paginatorOptions) {
			return $this->paginatorOptions;
		}

		$config = Api::_()->getConfig();
		if(isset($config['paginator']) && $config['paginator']){
			return $this->paginator = $config['paginator'];
		}
		return array();
	}

	public function setPaginator($paginator)
	{
		$this->paginator = $paginator;
		return $this;
	}

	public function getPaginator()
	{
		return $this->paginator;
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
