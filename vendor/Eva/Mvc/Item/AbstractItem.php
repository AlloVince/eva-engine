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


use Zend\Di\Di,
    Zend\Di\Config as DiConfig,
    Eva\Config\Config,
    Eva\Mvc\Model\AbstractModelService,
    Zend\Mvc\Exception\MissingLocatorException,
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
abstract class AbstractItem implements ServiceLocatorAwareInterface
{

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
    public function toArray()
    {
        $return = array();
        foreach ($this as $row) {
            $return[] = $this->getHydrator()->extract($row);
        }
        return $return;
    }

    public function setModel($model)
    {
        if(!$model instanceof AbstractModelService){
            throw new MissingLocatorException(printf('Model Service Locator not set by class %s',
            get_class($this)));
        }
        $this->model = $model;
        return $this;
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

    public function hasRelationships()
    {
        return 1;
    }

    public function getRelationships()
    {
        return $this->relationships;
    }

    public function create()
    {
    
    }

    public function save()
    {
    
    }

    public function remove()
    {
    
    }

    public function initialize()
    {
        if(!$dataSource && $this->model){
            $dataSource = $this->model->getDataSource();
        }

        if($dataSource){
            foreach($dataSource as $key => $data){
                if(is_array($data)){
                    $this->connections[$key] = $data;
                }
            }
        }
    }

}
