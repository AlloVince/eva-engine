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

namespace Eva\Mvc\Model;


use Eva\Api,
    Eva\Db\TableGateway\TableGateway,
    Eva\Mvc\Model\AbstractItem,
    Eva\Mvc\Model\AbstractItemList,
    Zend\Cache\StorageFactory as CacheStorageFactory,
    Zend\Mvc\MvcEvent;

/**
 * Mvc Abstract Model for item / itemlist / paginator
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Model
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class AbstractModel
{
    const CACHE_QUERY = 'query';
    const CACHE_META = 'meta';
    const CACHE_PAGINATOR = 'paginator';

    protected $events;
    protected $mvcEvent;

    protected $itemTable;
    protected $itemTableName;

    protected $itemName;
    protected $itemParams = array();
    protected $item;
    protected $itemAttrMap = array();
    protected $itemListParams = array();
    protected $itemList;

    protected $subItemsMap;
    protected $subItems;


    protected $cacheStorageFactory;
    protected $paginator;

    public function getItemTable()
    {
        if($this->itemTable){
            return $this->itemTable;
        }

        return $this->itemTable = Api::_()->getDbTable($this->itemTableName);
    }

    public function setItemTable(TableGateway $itemTable)
    {
        $this->itemTable = $itemTable;
        return $this;
    }

    public function getItemParams()
    {
        return $this->itemParams;
    }

    public function setItemParams($params)
    {
        $this->itemParams = $params;
        return $this;
    }


    public function setItem($item)
    {
        if($item && $item instanceof AbstractItem){
            $this->item = $item;
            return $this;
        }

        if($subItemsMap = $this->subItemMap){
            $subItems = array();
            foreach($item as $key => $value){
                if(!isset($subItemsMap[$key])){
                    continue;
                }
                $subItems[$key] = $value;
                unset($item[$key]);
            }
            $this->subItems = $subItems;
        }
        $this->item = $item;
        return $this;
    }

    public function setSubItemMap($subItemsMap)
    {
        $this->subItemMap = $subItemsMap;
        return $this;
    }

    public function getSubItem($dataKey = null)
    {
        if(isset($this->subItems[$dataKey])){
            return $this->subItems[$dataKey];
        }
        return false;
    }

    public function getSubItems()
    {
        return $this->subItems;
    }


    public function setItemAttrMap(array $itemAttrMap)
    {
        $this->itemAttrMap = $itemAttrMap;
        return $this;
    }

    public function getItemClass($itemArrayOrObject = null, $itemAttrConfig = array(), $itemClassName = null)
    {
        if(false === class_exists($itemClassName)){
            throw new \Zend\Mvc\Exception\InvalidArgumentException(sprintf(
                'Item class %s not exist',
                $itemClassName
            )); 
        }

        if(!is_array($itemArrayOrObject) && !$itemArrayOrObject instanceof \ArrayObject ){
            throw new \Zend\Mvc\Exception\InvalidArgumentException(sprintf(
                '%s Item type %s not exist',
                __method__,
                gettype($itemArrayOrObject)
            )); 
        }
        $item = new $itemClassName($itemArrayOrObject, $this, $itemAttrConfig);
        return $item;
    }

    
    public function getItem($itemArrayOrObject = null, $itemAttrConfig = array(), $itemClass = null)
    {
        if($this->item && $this->item instanceof AbstractItem){
            return $this->item;
        }

        $itemClassName = $itemClass ? $itemClass : get_class($this) . '\Item';
        $itemAttrConfig = array_merge($this->itemAttrMap, $itemAttrConfig);
        $itemArrayOrObject = $itemArrayOrObject ? $itemArrayOrObject : $this->item;
        return $this->item = $this->getItemClass($itemArrayOrObject, $itemAttrConfig, $itemClassName);
    }

    public function getItemArray($itemArrayOrObject = null, $itemAttrConfig = array())
    {
        $item = $this->getItem($itemArrayOrObject, $itemAttrConfig);
        return $item->toArray($itemAttrConfig);
    }


    public function getItemListParams()
    {
        return $this->itemListParams;
    }

    public function setItemListParams($params)
    {
        $this->itemListParams = $params;
        return $this;
    }

    public function getItemList()
    {
        return $this->itemList;
    }

    public function setItemList($itemList)
    {
        $this->itemList = $itemList;
        return $this;
    }

    public function getEvent()
    {
        if($this->mvcEvent && $this->mvcEvent instanceof MvcEvent){
            return $this->mvcEvent->getApplication()->events();
        }

        //TODO: throw exeption when no event set
        return new \Zend\EventManager\EventManager();
    }

    public function getMvcEvent()
    {
        return $this->mvcEvent;
    }

    public function setMvcEvent(MvcEvent $event)
    {
        $this->mvcEvent = $event;
        if($this->events){
            $event->getApplication()->events()->attach($this->events);
        }
    }

    public function cache(\Zend\Cache\Storage\Adapter\AdapterOptions $cacheOptions = null)
    {
        $cacheStorageFactory = $this->getCacheStorageFactory();
        if(!$cacheStorageFactory){
            return false;
        }
        $cacheAdapter = $cacheStorageFactory::getAdapter();
        return $cacheAdapter;
    }

    public function setCacheStorageFactory(CacheStorageFactory $cacheStorageFactory)
    {
        $this->cacheStorageFactory = $cacheStorageFactory;
        return $this;
    }

    public function getCacheStorageFactory()
    {
        return $this->cacheStorageFactory;
    }


    public function setPaginator(\Eva\Paginator\Paginator $paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function getPaginator(array $paginatorOptions = array(), $useDbTable = true)
    {
        $defaultPaginatorOptions = array(
            'itemCountPerPage' => 10,
            'pageRange' => 5,
            'pageNumber' => 1,
        );


        if(true === $useDbTable) {
            $itemTable = $this->getItemTable();
            if(!$itemTable) {
                return $this->paginator;
            }

            $count = $itemTable->getCount();
            if(!$count) {
                return $this->paginator;
            }

            $dbPaginatorOptions = $itemTable->getPaginatorOptions();

            $paginatorOptions = array_merge($defaultPaginatorOptions, $dbPaginatorOptions, $paginatorOptions);

            $count = (int) $count;
            $diConfig = array(
                'instance' => array(
                    'Zend\Paginator\Adapter\DbTableSelect' => array(
                        'parameters' => array(
                            'rowCount' => $count,
                            'select' => $itemTable->getSelect()
                        )
                    ),
                    'Eva\Paginator\Paginator' => array(
                        'parameters' => array(
                            'rowCount' => $count,
                            'adapter' => 'Zend\Paginator\Adapter\DbTableSelect',
                        ),
                    ),
                )
            );

        } else {
            
            $paginatorOptions = array_merge($defaultPaginatorOptions, $paginatorOptions);

        }

        foreach ($paginatorOptions as $key => $value) {
            if(false === in_array($key, array('itemCountPerPage', 'pageNumber', 'pageRange'))){
                continue;
            }
            
            $diConfig['instance']['Eva\Paginator\Paginator']['parameters'][$key] = $paginatorOptions[$key];
        }
        //p($diConfig['instance']['Eva\Paginator\Paginator']['parameters']);
        
        $di = new \Zend\Di\Di();
        $di->configure(new \Zend\Di\Config($diConfig));
        $paginator = $di->get('Eva\Paginator\Paginator');
       // \Zend\Di\Display\Console::export($di);
        return $this->paginator = $paginator;
    }
}
