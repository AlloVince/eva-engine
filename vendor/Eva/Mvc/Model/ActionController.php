<?php
namespace Eva\Mvc\Model;
abstract class AbstractModel
{
	protected $dbTable;

	public function getDbTable()
	{
		return $this->dbTable;
	}

	public function setDbTable($dbTable)
	{
		$this->dbTable = $dbTable;
		return $this;
	}
}
