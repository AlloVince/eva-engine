<?php
namespace Eva\Mvc\Controller;
use Eva\View\Model\ViewModel,
    Zend\Http\Request as HttpRequest,
    Zend\Http\PhpEnvironment\Response as HttpResponse,
    Zend\Mvc\MvcEvent;

abstract class RestfulController extends \Zend\Mvc\Controller\RestfulController
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

        $moduleName = \Eva\Core\Module::getModuleName($this);
        $controllerName = $this->getEvent()->getRouteMatch()->getParam('controller');
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
}
