<?php
namespace Eva\Db\TableGateway;
use Eva\Db\Adapter\Adapter,
	Eva\Db\ResultSet\ResultSet;
class AbstractTableGateway extends \Zend\Db\TableGateway\AbstractTableGateway
{
	protected $tablePrefix = 'eva_';
	protected $moduleName = '';
    protected $table = '';
	protected $tableName = '';

	public function getModuleName()
	{
		return $moduleName;
	}

	public function setModuleName($moduleName)
	{
		$this->moduleName = $moduleName;
		return $this;
	}

	public function initAdapter()
	{

	}

	public function initTableName()
	{
		$this->table = $this->tablePrefix . $this->moduleName . '_' . $this->tableName;
		return $this;
	}


    public function __construct(\Zend\Db\Adapter\Adapter $adapter = null)
	{
		if($adapter) {
        	$this->adapter = $adapter;
		} else {
			$this->initAdapter();
		}

		$this->initialize();
    }
}
