<?php
namespace Eva\Mvc\Model;


use Eva\Api,
    Eva\Db\TableGateway\TableGateway;

abstract class AbstractModel
{
    const CACHE_QUERY = 'query';
    const CACHE_META = 'meta';
    const CACHE_PAGINATOR = 'paginator';

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

    

    public function getEvent()
    {
    
    }


    public function cache($cacheData, $cacheType = '')
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
