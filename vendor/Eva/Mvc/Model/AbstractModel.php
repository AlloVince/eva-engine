<?php
namespace Eva\Mvc\Model;
abstract class AbstractModel
{

    const CACHE_QUERY = 'query';
    const CACHE_META = 'meta';
	const CACHE_PAGINATOR = 'paginator';


	protected $paginatorEnable = false;
	protected $paginatorOptions;
	protected $paginator;

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

	public function setPaginator($paginator)
	{
		$this->paginator = $paginator;
		return $this;
	}

	public function getPaginator()
	{
		$paginatorOptions = $this->getPaginatorOptions();
		$di = new \Zend\Di\Di();
		$di->instanceManager()->setParameters('Zend\Paginator\Adapter\DbSelect', array(
			'_select' => $this->getSelect()
		));
		$adapter = $di->get('Zend\Paginator\Adapter\DbSelect');
		$di->instanceManager()->setParameters('Zend\Paginator\Paginator', array(
			'_adapter' => $adapter
		));


		$paginator = new \Zend\Paginator\Paginator($adapter);
		//$paginator = $di->get('Zend\Paginator\Paginator');
		return $paginator;
	}
}
