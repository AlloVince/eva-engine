<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */
namespace Eva\Db\TableGateway;
use Zend\Db\Adapter\Adapter,
    Zend\Db\Sql\Select,
    Zend\Db\Sql\Expression,
    Zend\Db\TableGateway\AbstractTableGateway,
    Eva\Db\ResultSet\ResultSet,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Eva\Db\Exception;
use Zend\Stdlib\Parameters;

/**
 *
 * @category   Eva
 * @package    Eva_Db
 */
class TableGateway extends AbstractTableGateway  implements ServiceLocatorAwareInterface
{
    const ROW_COUNT_COLUMN = 'eva_row_count';

    protected $tablePrefix;
    protected $moduleTableName;
    protected $table;
    protected $tableName;
    protected $primaryKey;
    protected $uniqueIndex;

    protected $select;
    protected $selectOptions;
    protected $lastSelectString;

    protected $enableCount = false;
    protected $autoLimit = true;
    protected $lastSelectCount;

    protected $paginatorOptions = array();

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    protected $noResult = false;

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if($params->page){
            $this->page($params->page);
        }
        
        if ($params->noLimit) {
            $this->disableLimit();
        }

        return $this;
    }

    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getNoResult()
    {
        return $this->noResult;
    }

    public function setNoResult($noResult)
    {
        $this->noResult = $noResult;
        return $this;
    }

    public function getSelect()
    {
        return $this->select ? $this->select : $this->sql->select();
    }

    public function setSelect($select)
    {
        $this->select =  $select;
        return $this;
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


        $config = $this->getServiceLocator()->get('Configuration');
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

    public function getUniqueIndex()
    {
        return $this->uniqueIndex;
    }

    public function reset()
    {
        $this->select = null;
        $this->selectOptions = array(); 
        $this->enableCount = false;
        $this->autoLimit = true;
        $this->noResult = false;
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

            $select = $this->getSelect();
            call_user_func_array(array($select, $method), $arguments);

            //Cache select options here
            $this->selectOptions[$method] = isset($arguments[0]) ? $arguments[0] : null;

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
        $page = $page ? $page : 1;

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
        //Use magic call offset here to keep params
        $this->offset($offset);
        return $this;
    }

    public function find($findCondition = null, array $findOptions = array())
    {
        if($this->getNoResult()){
            $this->reset();
            return array(); 
        }

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
        $select->reset('order');

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
        if($this->autoLimit && (!isset($selectOptions['limit']) || !$selectOptions['limit'])) {
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
            $res = $this->insert($set);
            $this->reset();
            return $res;
        }

        $res = $this->update($set, $where);
        $this->reset();
        return $res;
    }

    public function create(array $set = array())
    {
        if(!$set){
            $this->reset();
            return false;
        }
        $res = $this->insert($set);
        $this->reset();
        return $res;
    }

    public function remove()
    {
        $selectOptions = $this->selectOptions;
        $where = isset($selectOptions['where']) ? $selectOptions['where'] : array();
        if(!$selectOptions || !$where){
            $this->reset();
            return false;
        }

        $res = $this->delete($where);
        $this->reset();
        return $res;
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

    public function disableLimit()
    {
        $this->autoLimit = false;
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
            $this->adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
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
