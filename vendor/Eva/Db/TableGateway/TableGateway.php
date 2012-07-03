<?php
namespace Eva\Db\TableGateway;
use Eva\Api,
    Zend\Db\Adapter\Adapter,
    Zend\Db\Sql\Select,
    Zend\Db\Sql\Expression,
    Eva\Db\ResultSet\ResultSet,
    Eva\Db\Exception;
class TableGateway extends \Zend\Db\TableGateway\AbstractTableGateway
{
    const ROW_COUNT_COLUMN = 'eva_row_count';

    protected $tablePrefix;
    protected $moduleTableName;
    protected $table;
    protected $tableName;
    protected $primaryKey;

    protected $select;
    protected $selectOptions;
    protected $lastSelectString;

    protected $enableCount = false;
    protected $lastSelectCount;

    protected $paginatorOptions = array();

    public function getSelect()
    {
        return $this->select ? $this->select : $this->sql->select();
    }

    public function getUpdate()
    {
        return $this->update ? $this->update : $this->sql->update();
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
        $this->enableCount = false;
        return $this;
    }

    public function __call($method, $arguments)
    {
        //Magic call below method in Db\Sql\Select
        $allowMagicCalls = array('where', 'from', 'columns', 'join', 'group', 'having', 'order', 'limit', 'offset');

        if(true === in_array($method, $allowMagicCalls)){
            if (!$this->isInitialized) {
                $this->initialize();
            }

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

            //Where maybe have multi columns
            if($method == 'where'){
                $this->selectOptions['where'] = $select->where;
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
        return $this->fetchCount();
    }

    public function page($page = 1)
    {
        //Maybe call find without select condition
        if (!$this->isInitialized) {
            $this->initialize();
        }
        $page = (int) $page;
        $page = !$page ? 1 : $page;

        $select = $this->getSelect();
        $selectOptions = $this->selectOptions;
        if(isset($selectOptions['limit']) && $selectOptions['limit']) {
            $limit = $selectOptions['limit'];
        } else {
            $limit = 10;
        }
        $this->paginatorOptions['itemCountPerPage'] = (int) $limit;
        $this->paginatorOptions['pageNumber'] = $page;

        $offset = ($page - 1) * $limit;
        $select->offset($offset);
        return $this;
    }

    public function find($findCondition = null, array $findOptions = array())
    {
        //Maybe call find without select condition
        if (!$this->isInitialized) {
            $this->initialize();
        }

        if(!$findCondition && !$findOptions){
            return $this->fetchAll($this->getSelect());
        }

        if(true === is_numeric($findCondition)){
            return $this->findByNumber($findCondition);
        } elseif(true === is_string($findCondition)){
            return $this->findByString($findCondition, $findOptions);
        } elseif(true === is_array($findCondition)){
            return $this->findByArray($findCondition);
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s not allow input find condition type %s',
                __METHOD__,
                gettype($findCondition)
            ));
        }
    }

    protected function findByNumber($findNumber)
    {
        $primaryKey = $this->primaryKey;
        if(!$primaryKey){
            throw new Exception\InvalidArgumentException(sprintf(
                'No primary key set in %s',
                __METHOD__
            ));
        }
        if(false === is_string($primaryKey)){
            throw new Exception\InvalidArgumentException(sprintf(
                'Only allow single primary key in %s',
                __METHOD__
            ));
        }

        $this->where(array(
            $primaryKey => $findNumber
        ));
        return $this->fetchOne($this->select);
    }

    protected function findByString($findString, array $findArray = array())
    {
        $findString = strtolower($findString);
        switch($findString) {
            case 'one' :
                return $this->fetchOne($this->getSelect());
            case 'count' :
                return $this->fetchCount($this->getSelect());
            case 'all' :
                return $this->findByArray($findArray);
        }
        return $select;
    }

    protected function findByArray(array $findArray)
    {
        $findOptions = array(
            'where' => false,
            'from' => false,
            'columns' => false,
            'join' => false,
            'group' => false,
            'having' => false,
            'order' => false,
            'page' => false,
            'limit' => false,    
            'offset' => false, 
            'enablePaginator' => false,
        );

        $select = $this->getSelect();
        return $this->fetchAll($select);
    }

    protected function fetchOne(Select $select)
    {
        $this->limit(1);
        $resultSet = $this->selectWith($select);
        $this->lastSelectString = $select->getSqlString();
        $this->reset();
        if(!$resultSet){
            return array();
        }
        return $resultSet->current();
    }

    protected function fetchCount(Select $select)
    {
        $select->limit(1);
        $select->offset(null);
        //NOTE: no method could reset order here
        //$select->order(array());

        $countColumnName = self::ROW_COUNT_COLUMN;
        if($this->primaryKey && is_string($this->primaryKey)){
            $select->columns(array(
                $countColumnName    => new Expression("COUNT($this->primaryKey)")
            ));
        } else {
            $select->columns(array(
                $countColumnName    => new Expression('COUNT(*)')
            ));
        }

        //p($select->getSqlString());
        
        $resultSet = $this->selectWith($select);
        if(false === $this->enableCount){
            $this->lastSelectString = $select->getSqlString();
            $this->reset();
        }

        if(!$resultSet){
            return 0;
        }

        $resultSet = $resultSet->current();
        return $this->lastSelectCount = $resultSet->$countColumnName;
    }

    protected function fetchAll(Select $select)
    {
        if(true === $this->enableCount){
            $countSelect = clone $select;
            $this->fetchCount($countSelect);
        }

        $selectOptions = $this->selectOptions;


        //Auto enable limit to prevent load full table
        if(!isset($selectOptions['limit']) || !$selectOptions['limit']) {
            $select->limit(10);
        }

        $resultSet = $this->selectWith($select);

        $this->lastSelectString = $select->getSqlString();
        //p($select->getSqlString());
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

    public function save(array $set = array())
    {
        $selectOptions = $this->selectOptions;
        $where = isset($selectOptions['where']) ? $selectOptions['where'] : array();
        if(!$selectOptions || !$where){
            return $this->insert($set);
        }

        return $this->update($set, $where);    
    }

    public function create(array $set = array())
    {
        if(!$set){
            return false;
        }
        return $this->insert($set);
    }

    public function remove()
    {
        $selectOptions = $this->selectOptions;
        $where = isset($selectOptions['where']) ? $selectOptions['where'] : array();
        if(!$selectOptions || !$where){
            return false;
        }

        return $this->delete($where);    
    }


    public function getCount()
    {
        return $this->lastSelectCount;
    }


    public function changeAdapter($adapterArrayOrObject)
    {
    }

    public function enableCount()
    {
        $this->enableCount = true;
        return $this;
    }

    public function disableCount()
    {
        $this->enableCount = false;
        return $this;
    }

    public function getPaginatorOptions()
    {
        return $this->paginatorOptions;
    }

    public function initialize()
    {
        if ($this->isInitialized) {
            return;
        }

        if(!$this->adapter) {
            $this->adapter = Api::_()->getDbAdapte();
        }

        $this->initTableName();
        parent::initialize();
    }

    public function __construct(Adapter $adapter = null)
    {
        if($adapter) {
            $this->adapter = $adapter;
        }
    }
}
