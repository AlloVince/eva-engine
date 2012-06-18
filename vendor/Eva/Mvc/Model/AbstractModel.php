<?php
namespace Eva\Mvc\Model;


use Eva\Api,
    Eva\Db\TableGateway\TableGateway;

abstract class AbstractModel
{

    const CACHE_QUERY = 'query';
    const CACHE_META = 'meta';
    const CACHE_PAGINATOR = 'paginator';


    protected $paginatorEnable = false;
    protected $paginatorOptions;
    protected $paginator;

    protected $itemTable;

    public function getItemTable()
    {
        return $this->itemTable;
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


    public function setPaginatorOptions($paginatorOptions)
    {
        $this->paginatorOptions = $paginatorOptions;
        return $this;
    }

    public function getPaginatorOptions()
    {
        if($this->paginatorOptions) {
            return $this->paginatorOptions;
        }

        $config = Api::_()->getConfig();

        $paginatorOptions = array(
            'itemCountPerPage' => 10,
            'currentPageNumber' => 1,
            'pageRange' => 5,
        );

        return $paginatorOptions;

        /*
        if(isset($config['paginator']) && $config['paginator']){
            return $this->paginator = $config['paginator'];
        }
         */
    }

    public function setPaginator(\Zend\Paginator\Paginator $paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function getPaginator()
    {
        return $this->paginator;

        /*
        $paginatorOptions = $this->getPaginatorOptions();
        $di = new \Zend\Di\Di();
        $di->instanceManager()->setParameters('Zend\Paginator\Adapter\DbSelect', array(
            '_select' => $this->getItemTable()->getSelect()
        ));
        $adapter = $di->get('Zend\Paginator\Adapter\DbSelect');
        $di->instanceManager()->setParameters('Zend\Paginator\Paginator', array(
            'adapter' => $adapter,
        ));

        $paginator = $di->get('Zend\Paginator\Paginator');
        //$paginator = new \Zend\Paginator\Paginator($adapter);
        return $paginator;
         */
    }
}
