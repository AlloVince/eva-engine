<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Eva\Mvc\Item;


use Eva\Mvc\Model\AbstractModelService,
    Zend\Mvc\Exception,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\Stdlib\Hydrator\ClassMethods;
use ArrayObject;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\HydratorInterface;
use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;

/**
 * Mvc Abstract Model for item / itemlist / paginator
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Model
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class AbstractItem extends ArrayIterator implements Iterator, ServiceLocatorAwareInterface
{

    /**
     * @var null|int
     */
    protected $count = null;


    /**
     * @var Eva\Mvc\Model\AbstractModelService
     */
    protected $model;

    /**
     * @var Iterator|IteratorAggregate
     */
    protected $dataSource = null;

    /**
     * @var DbTable | Webservice
     */
    protected $dataSourceType = 'DbTable';

    protected $dataSourceClass = '';

    protected $relationships = array();

    protected $initialized = false;

    /**
     * @var HydratorInterface
     */
    protected $hydrator = null;

    /**
     * @var null
     */
    protected $objectPrototype = null;

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

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


    /**
     * Set the row object prototype
     *
     * @param  object $objectPrototype
     * @return ResultSet
     */
    public function setObjectPrototype($objectPrototype)
    {
        if (!is_object($objectPrototype)) {
            throw new Exception\InvalidArgumentException(
                'An object must be set as the object prototype, a ' . gettype($objectPrototype) . ' was provided.'
            );
        }
        $this->objectPrototype = $objectPrototype;
        return $this;
    }

    /**
     * Set the hydrator to use for each row object
     *
     * @param HydratorInterface $hydrator
     * @return HydratingResultSet
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     * Get the hydrator to use for each row object
     *
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * Cast result set to array of arrays
     *
     * @return array
     * @throws Exception\RuntimeException if any row is not castable to an array
     */
    public function toArray($map = null)
    {
        if($map){
            foreach($map as $key => $method){
                $this->$method();
            }
        }
        $return = (array) $this->dataSource;
        return $return;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        if(!$model instanceof AbstractModelService){
            throw new Exception\MissingLocatorException(printf('Model Service Locator not set by class %s',
            get_class($this)));
        }
        $this->model = $model;
        $this->initialize();
        return $this;
    }

    public function getDbTable()
    {
        $tableClassName = $this->dataSourceClass;
        $serviceManager = $this->getServiceLocator();
        if($serviceManager->has($tableClassName)){
            return $serviceManager->get($tableClassName);
        }

        $serviceManager->setFactory($tableClassName, function(ServiceLocatorInterface $serviceLocator) use ($tableClassName){
            return new $tableClassName($serviceLocator->get('Zend\Db\Adapter\Adapter'));
        });

        return $serviceManager->get($tableClassName);
    }

    public function getWebService()
    {
    
    }

    public function getDataClass()
    {
        if($this->dataSourceType == 'WebService'){
            return $this->getWebService();
        }

        return $this->getDbTable();
    }

    /**
     * Get the data source used to create the result set
     *
     * @return null|Iterator
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }


    public function setDataSource($dataSource)
    {
        if (is_array($dataSource)) {
            // its safe to get numbers from an array
            $first = current($dataSource);
            reset($dataSource);
            $this->count = count($dataSource);
            $this->dataSource = new ArrayIterator($dataSource);
        } elseif ($dataSource instanceof IteratorAggregate) {
            $this->dataSource = $dataSource->getIterator();
        } elseif ($dataSource instanceof Iterator) {
            $this->dataSource = $dataSource;
        } else {
            throw new Exception\InvalidArgumentException('DataSource provided is not an array, nor does it implement Iterator or IteratorAggregate');
        }
        return $this;
    }

    public function hasRelationships()
    {
        $hasRelationships = false;
        foreach($this->relationships as $key => $relationship){
            if(isset($relationship['dataSource']) && $relationship['dataSource']){
                $hasRelationships = true;
                break;
            }
        }
        return $hasRelationships;
    }

    public function getRelationships()
    {
        $relationships = new ArrayObject();

        $model = $this->getModel();
        foreach($this->relationships as $key => $relationship){
            if(isset($relationship['dataSource']) && $relationship['dataSource'] && $relationship['targetEntity']){
                $relItem = $model->getItem($relationship['targetEntity']); 
                $relItem->setDataSource($relationship['dataSource']);
                $relationships[$key] = $relItem;
            }
        }
        return $relationships;
    }

    public function selfList()
    {
    }

    public function self()
    {
        $dataClass = $this->getDataClass();
        $where = $this->toArray();
        if(!$where){
            throw new Exception\InvalidArgumentException(printf('No item select where condition set in class %'), get_class($this));
        }
        $dataSource = $dataClass->where($where)->find('one');
        if(!$dataSource){
            $this->setDataSource(array());
        } else {
            $this->setDataSource($dataSource);
        }
        return $this;
    }

    public function relationship()
    {
    
    }

    public function proxy()
    {
    
    }

    public function create()
    {
        $dataClass = $this->getDataClass();
        $data = $this->toArray(
            isset($this->map['create']) ? $this->map['create'] : array()
        );
        $primaryKey = $dataClass->getPrimaryKey();
        if($dataClass->create($data)){
            $this->$primaryKey = $dataClass->getLastInsertValue();
        }
        return $this->$primaryKey;
    }

    public function save()
    {
    
    }

    public function remove()
    {
    
    }

    public function __get($name) 
    {
        if(isset($this->dataSource[$name])){
            return $this->dataSource[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->dataSource[$name] = $value;
        return $this;
    }


    public function initialize()
    {
        if(true === $this->initialized){
            return $this;
        }

        $dataSource = $this->dataSource;
        if(!$dataSource && $this->model){
            $dataSource = $this->model->getDataSource();
        }

        if($dataSource){
            foreach($dataSource as $key => $data){
                if(is_array($data)){
                    $this->relationships[$key]['dataSource'] = $data;
                    unset($dataSource[$key]);
                }
            }
        }

        $this->setDataSource($dataSource);



        //$hydrator = new Hydrator($dataSource);
        //$hydrator->hydrate($dataSource, &$this);
        //$this->setHydrator($hydrator);

        $this->initialized = true;
        return $this;
    }

    /**
     * Iterator: move pointer to next item
     *
     * @return void
     */
    public function next()
    {
        $this->dataSource->next();
    }

    /**
     * Iterator: retrieve current key
     *
     * @return mixed
     */
    public function key()
    {
        return $this->dataSource->key();
    }

    /**
     * Iterator: get current item
     *
     * @return array
     */
    public function current()
    {
        return $this->dataSource->current();
    }

    /**
     * Iterator: is pointer valid?
     *
     * @return bool
     */
    public function valid()
    {
        return $this->dataSource->valid();
    }

    /**
     * Iterator: rewind
     *
     * @return void
     */
    public function rewind()
    {
        $this->dataSource->rewind();
        // return void
    }

    /**
     * Countable: return count of rows
     *
     * @return int
     */
    public function count()
    {
        if ($this->count !== null) {
            return $this->count;
        }
        $this->count = count($this->dataSource);
        return $this->count;
    }

}
