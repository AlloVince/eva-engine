<?php
namespace Eva\Mvc\Model;


use Eva\Api,
    Eva\Db\TableGateway\TableGateway,
    Zend\Mvc\MvcEvent;

abstract class AbstractModel
{
    const CACHE_QUERY = 'query';
    const CACHE_META = 'meta';
    const CACHE_PAGINATOR = 'paginator';

    protected $events;
    protected $mvcEvent;

    protected $itemName;
    protected $item;
    protected $itemList;

    protected $paginator;

    protected $itemTable;
    protected $itemTableName;

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

    
    public function getItem($itemArrayOrObject, $itemAttrConfig = array())
    {
        if($this->item){
            return $this->item;
        }

        $itemClassName = get_class($this) . '\Item';

        $item = new $itemClassName($itemArrayOrObject, $this, $itemAttrConfig);

        return $this->item = $item;
    }

    public function getItemArray($itemArrayOrObject, $itemAttrConfig = array())
    {
        $item = $this->getItem($itemArrayOrObject, $itemAttrConfig);
        return $item->toArray($itemAttrConfig);
    }

    public function setItem(AbstractItem $item)
    {
        $this->item = $item;
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

    public function setCache($cacheData, $cacheType = '')
    {
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
        $di->configure(new \Zend\Di\Configuration($diConfig));
        $paginator = $di->get('Eva\Paginator\Paginator');
       // \Zend\Di\Display\Console::export($di);
        return $this->paginator = $paginator;
    }
}
