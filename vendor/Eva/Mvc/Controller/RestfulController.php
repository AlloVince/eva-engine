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

namespace Eva\Mvc\Controller;
use Eva\View\Model\ViewModel,
    Eva\Mvc\Exception,
    Zend\Http\Request as HttpRequest,
    Zend\Http\PhpEnvironment\Response as HttpResponse,
    Zend\Mvc\MvcEvent;

/**
 * Add Eva RESTFul resource
 * 
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Controller
 */
abstract class RestfulController extends \Zend\Mvc\Controller\AbstractRestfulController
{
    protected $renders = array();
    protected $addResources = array();
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

        $routeMatch = $this->getEvent()->getRouteMatch();
        $moduleName = $routeMatch->getParam('module');
        $moduleNamespace = $routeMatch->getParam('moduleNamespace');
        $controllerName = $routeMatch->getParam('controllerName');
        $action = $routeMatch->getParam('action');
        $id = $routeMatch->getParam('id');
        $request = $this->getRequest();
        $method = strtolower($request->getMethod());
        if(!$moduleName || !$controllerName || !$method){
            throw new \Eva\Core\Exception\RestfulException('Restful route argument not exist');
        }

        switch($method) {
            case 'put':
            case 'delete':
                break;
            case 'post':
                //POST method could pretend as put or delete method
                $postParams = $request->getPost();
                $methodRecover = isset($postParams['_method']) && $postParams['_method'] ? $postParams['_method'] : '';
                if($methodRecover == 'put' || $methodRecover == 'delete'){
                    $method = $methodRecover;
                }
                break;
            default:
                if($id){
                    $method = 'get';
                } else {
                    $method = 'index';
                }
        }

        $resource = '';
        $render = $method;
        if(true === in_array($action, $this->getAddResources())){
            $resource = $action;
            $render = $resource;
        } elseif(true === in_array($id, $this->getAddResources())){
            $resource = $id;
            $render = $resource;
        }
        $render = $controllerName . '/' . $render;

        $resource = $method . ucfirst($controllerName) . ucfirst($resource);
        $function = 'rest' . ucfirst($resource);

        return $this->restfulResource = array(
            'module' => $moduleName,
            'moduleNamespace' => $moduleNamespace,
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
            throw new Exception\InvalidArgumentException(sprintf('Request restful resource %s not exist', $function));
        }

        $variables = $this->$function();
        if($variables instanceof \Zend\View\Model\ModelInterface || $variables instanceof \Zend\Http\PhpEnvironment\Response){
            return $variables;
        }

        $model = new ViewModel();
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
