<?php
namespace Eva\Db\TableGateway;
use Eva\Db\Adapter\Adapter,
	Eva\Db\ResultSet\ResultSet;
class TableGateway extends \Zend\Db\TableGateway\TableGateway
{
	protected $tablePrefix = 'eva_';

	protected $moduleName = '';

    public function __construct(\Zend\Db\Adapter\Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
	{
		$tableName = $this->tablePrefix . $this->moduleName . '_' . $this->tableName;
        return parent::__construct($tableName, $adapter, $databaseSchema, $selectResultPrototype);
    }
}
