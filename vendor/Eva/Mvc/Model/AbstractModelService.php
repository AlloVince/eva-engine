<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Eva\Mvc\Model;


use Zend\Di\Di,
    Zend\Di\Config as DiConfig,
    Eva\Config\Config,
    Eva\Mvc\Item\AbstractItem,
    Zend\Mvc\Exception\MissingLocatorException,
    Eva\Mvc\Exception\InvalidEventException,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Mvc Abstract Model for item / itemlist / paginator
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Model
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class AbstractModelService implements ServiceLocatorAwareInterface
{
    protected $events = array(
        'get.precache',
        'get.pre',
        'get',
        'get.post',
        'get.postcache',
        'getList.precache',
        'getList.pre',
        'getList',
        'getList.post',
        'getList.postcache',
        'create.pre',
        'create',
        'create.post',
        'save.pre',
        'save',
        'save.post',
        'remove.pre',
        'remove',
        'remove.post',
    );


    protected $itemClass;
    protected $item;
    protected $itemList;
    protected $dataSource;

    protected $cacheStorageFactory;
    protected $paginator;

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
        $this->init();
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

    public function setItem($item)
    {
        if($item instanceof AbstractItem){
            $this->item = $item;
        }

        //Clear last item and get new one
        if($this->item){
            $this->item->setDataSource($item);
        } else {
            $this->dataSource = $item;
        }
        return $this;
    }

    public function getItem($itemClass = null)
    {
        if(!$itemClass){
            $itemClass = $this->getItemClass();
        }

        if($this->serviceLocator->has($itemClass)){
            return $this->serviceLocator->get($itemClass);
        }

        $model = &$this;
        $this->serviceLocator->setFactory($itemClass, function(ServiceLocatorInterface $serviceLocator) use ($itemClass, $model){
            $item = new $itemClass();
            $item->setModel($model);
            return $item;
        });

        if($itemClass == $this->getItemClass()){
            return $this->item = $this->serviceLocator->get($itemClass);
        }
        return $this->serviceLocator->get($itemClass);
    }

    public function getDataSource()
    {
        return $this->dataSource;
    }

    public function getEvent()
    {
        return $this->getServiceLocator()->get('EventManager'); 
    }

    public function trigger($event, $target = null, $argv = array(), $callback = null)
    {
        if(false === in_array($event, $this->events)){
            throw new InvalidEventException(printf('Invalid event %s not allow to trigger', $event));
        }

        $className = get_class($this);
        $event = str_replace('\\', '.', strtolower($className)) . '.' . $event;
        return $this->getEvent()->trigger($event, $target, $argv, $callback);
    }


    public function getCache(array $config = array())
    {
        if($this->cacheStorageFactory){
            return $this->cacheStorageFactory;
        }

        $di = new Di();
        $diConfig = array(
            'definition' => array(
                'class' => array(
                    'Zend\Cache\Storage\Adapter' => array(
                        'instantiator' => array(
                            'Eva\Cache\StorageFactory',
                            'factory'
                        ),
                    ),
                    'Eva\Cache\StorageFactory' => array(
                        'methods' => array(
                            'factory' => array(
                                'cfg' => array(
                                    'required' => true,
                                    'type' => false
                                )
                            )
                        ),
                    ),
                ),
            ),
            'instance' => array(
                'Eva\Cache\StorageFactory' => array(
                    'parameters' => array(
                        'cfg' => array(
                            'adapter' => array(
                                'name' => 'filesystem',
                                'options' => array(
                                    'cacheDir' => EVA_ROOT_PATH . '/data/cache/model/',
                                ),
                            ),
                            'plugins' => array('serializer')
                        ),
                    )
                ),
            )
        );

        $globalConfig = $this->serviceLocator->get('Configuration');
        $globalConfig = isset($globalConfig['cache']['model']) ? $globalConfig['cache']['model'] : array();
        $diConfig = Config::mergeArray($diConfig, $globalConfig, $config);
        $di->configure(new DiConfig($diConfig));
        return $this->cacheStorageFactory = $di->get('Eva\Cache\StorageFactory');
    }

    public function getItemClass()
    {
        if($this->itemClass){
            return $this->itemClass;
        }

        $className = get_class($this);
        return $this->itemClass = str_replace('\Model\\', '\Item\\', $className);
    }

    public function init()
    {
        //$this->serviceLocator->setInvokableClass('ModelCache', 'Eva\Cache\Service\ModelCache');
        //$itemClass = $this->getItemClass(); 
        //$this->serviceLocator->setInvokableClass($itemClass, $itemClass);
    }

}
