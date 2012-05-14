<?php
namespace Eva\Mvc\Controller;
use Eva\View\Model\ViewModel,
	Zend\Http\Request as HttpRequest,
    Zend\Http\PhpEnvironment\Response as HttpResponse,
    Zend\Stdlib\RequestInterface as Request,
    Zend\Stdlib\ResponseInterface as Response,
	Zend\Mvc\MvcEvent;

abstract class RestfulModuleController extends \Zend\Mvc\Controller\RestfulController
{
	protected $addResources = array();
	protected $renders = array();
	protected $restfulResource = array();

	public function getAddResources()
	{
		return $this->addResources;	
	}

	public function getRestfulResource()
	{
		if($this->restfulResource) {
			return $this->restfulResource;
		}

		$routeParams = $this->getEvent()->getRouteMatch()->getParams();
		$moduleName = $routeParams['module'];
		$controllerName = $routeParams['controllerName'];
		$method = strtolower($this->getRequest()->getMethod());
		if(!$moduleName || !$controllerName || !$method){
			throw new \Eva\Core\Exception\RestfulException('Restful route argument not exist');
		}

		switch($method) {
			case 'put':
			case 'delete':
				break;
			case 'post':
				//POST method could pretend as put or delete method
				if($methodRecover = strtolower($this->getRequest()->getParam('_method'))
					&& ($methodRecover == 'put' || $methodRecover == 'delete')
				){
					$method = $methodRecover;
					break;
				}
			default:
				if($id = $this->getEvent()->getRouteMatch()->getParam('id')){
					$method = 'get';
				} else {
					$method = 'index';
				}
		}

		$resource = '';
		$render = $method;
		if(true === in_array($id, $this->getAddResources())){
			$resource = $id;
			$render = $resource;
		}
		$render = $controllerName . '/' . $render;

		$resource = $method . ucfirst($controllerName) . ucfirst($resource);
		$function = 'rest' . ucfirst($resource);

		return $this->restfulResource = array(
			'module' => $moduleName,
			'controller' => $controllerName,	
			'method' => $method,
			'resource' => $resource,
			'function' => $function,
			'render' => $render,
		);
	}

	protected function restfulAutoRender()
	{
		$resource = $this->getRestfulResource();
		$function = $resource['function'];
		$render = $resource['render'];

		if(false === method_exists($this, $function)) {
			throw new \Eva\Core\Exception\RestfulException('Request restful resource not exist');
		}

		$model = new ViewModel();
		$variables = $this->$function();
		if($variables) {
			$model->setVariables($variables);
		}
		if(isset($this->renders[$function]) && $this->renders[$function]){
			$render = $this->renders[$function];
		}
		$model->setTemplate($render);
		return $model;
	}

	public function getList()
	{
		return $this->restfulAutoRender();
	}

	public function get($id)
	{
		return $this->restfulAutoRender();
	}

	public function create($data)
	{
		return $this->restfulAutoRender();
	}

	public function update($id, $data)
	{
		return $this->restfulAutoRender();
	}

	public function delete($id)
	{
		return $this->restfulAutoRender();
	}


    public function execute(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            throw new \DomainException('Missing route matches; unsure how to retrieve action');
        }

        $request = $e->getRequest();
        $action  = $routeMatch->getParam('action', false);
        if ($action) {
            // Handle arbitrary methods, ending in Action
            $method = static::getMethodFromAction($action);
            if (true === method_exists($this, $method)) {
            	$return = $this->$method();
            	goto complete;
            }
		} 

		// RESTful methods
		switch (strtolower($request->getMethod())) {
		case 'get':
			if (null !== $id = $routeMatch->getParam('id')) {
				$return = $this->get($id);
				break;
			}
			if (null !== $id = $request->query()->get('id')) {
				$return = $this->get($id);
				break;
			}
			$return = $this->getList();
			break;
		case 'post':
			$return = $this->create($request->post()->toArray());
			break;
		case 'put':
			if (null === $id = $routeMatch->getParam('id')) {
				if (!($id = $request->query()->get('id', false))) {
					throw new \DomainException('Missing identifier');
				}
			}
			$content = $request->getContent();
			parse_str($content, $parsedParams);
			$return = $this->update($id, $parsedParams);
			break;
		case 'delete':
			if (null === $id = $routeMatch->getParam('id')) {
				if (!($id = $request->query()->get('id', false))) {
					throw new \DomainException('Missing identifier');
				}
			}
			$return = $this->delete($id);
			break;
		default:
			throw new \DomainException('Invalid HTTP method!');
		}

		complete:
		$e->setResult($return);
		return $return;
	}
}
