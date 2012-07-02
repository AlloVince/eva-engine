<?php
namespace Eva\Mvc\Router\Http;

use \Traversable,
    Zend\Mvc\Router\Http,
    Zend\Mvc\Router\RouteMatch,
    Zend\Stdlib\ArrayUtils,
    Zend\Stdlib\RequestInterface as Request,
    Zend\Mvc\Router\Exception;

class ModuleRoute implements \Zend\Mvc\Router\Http\RouteInterface
{
    protected $moduleNames = array();

    protected $protectedNamespaces = array();

    public function setModuleNames(array $moduleNames)
    {
        $this->moduleNames = $moduleNames;
    }

    public function getModuleNames()
    {
        if($this->moduleNames){
            return $this->moduleNames;
        }

        $moduleLoaded = \Eva\Api::_()->getModuleLoaded();

        if($moduleLoaded){
            return $moduleLoaded;
        }

        if($appConfig && isset($appConfig['modules'])){
            return $appConfig['modules'];
        }
        /*
        if(false === \Eva\Registry::isRegistered("appConfig")){
            return array();
        }

        $appConfig = \Eva\Registry::get("appConfig");
        if($appConfig && isset($appConfig['modules'])){
            return $appConfig['modules'];
        }
        */

        return array();
    }


    public function setProtectedNamespaces(array $protectedNamespaces = array())
    {
        $this->protectedNamespaces = $protectedNamespaces;
    }

    public function getProtectedNamespaces()
    {
        if($this->protectedNamespaces){
            return $this->protectedNamespaces;
        }

        /*
        if(false === \Eva\Registry::isRegistered("appConfig")){
            return array();
        }

        $appConfig = \Eva\Registry::get("appConfig");
        if($appConfig && isset($appConfig['protected_module_namespace'])){
            return $appConfig['protected_module_namespace'];
        }
        */

        return array();
    }


    public function __construct($route, array $moduleNames = array(), array $protectedNamespaces = array())
    {
        $this->route    = $route;
        $this->moduleNames = $moduleNames;
        $this->protectedNamespaces = $protectedNamespaces;
    }

    public static function factory($options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        return new static(array(), array());
    }

    public function match(Request $request, $pathOffset = null)
    {
        if (!method_exists($request, 'getUri')) {
            return null;
        }

        $uri  = $request->getUri();
        $path = $uri->getPath();

        //Remove last /
        $pathTrim = trim(strtolower($path), '/');
        $pathArray = explode('/', $pathTrim);
        $pathMaxLevel = count($pathArray);

        $loadedModules = $this->getModuleNames();
        if(!$loadedModules) {
            return null;
        }

        $checkInList = function($name, $array) {
            if(!$array) {
                return false;
            }
            $name = ucfirst($name);
            return in_array($name, $array);
        };

        $protectedModuleNamespace = $this->getProtectedNamespaces();
        $moduleName = 'core';
        $moduleNamespace = '';
        $controllerName = $moduleName;
        $actionName = 'index';
        $id = '';


        //Check prefix
        if($pathArray[0] && true === $checkInList($pathArray[0], $protectedModuleNamespace)){
            $moduleNamespace = array_shift($pathArray);
            $pathMaxLevel = count($pathArray);

            if($pathMaxLevel === 0){
                $pathArray = array('');
            }
        }

        //Path is exactly / or /admin
        if(!$pathArray[0]){
            $moduleNamespace = $moduleNamespace ? $moduleNamespace : $moduleName;
        }

        //Level 1 :Path is / or /module
        if($pathArray[0]) {

            //Level 1 :Path is /123
            if(is_numeric($pathArray[0])){
                $moduleNamespace = $moduleNamespace ? $moduleNamespace : $moduleName;
                $actionName = 'get';
                $id = $pathArray[0];
                $controllerName = $moduleName;

                goto complete;
            } else {
                $moduleName = $pathArray[0];
                if(strpos($moduleName, '-') === false){
                    $moduleNamespace = $moduleNamespace ? $moduleNamespace : $moduleName;
                } else {
                    $moduleNameArray = explode('-', $moduleName);
                    $moduleName = $moduleNameArray[0];
                    $moduleNamespace = $moduleNamespace ? $moduleNamespace : $moduleNameArray[1];            
                }            
            }

        }
        $controllerName = $moduleName;


        //Module not loaded
        if(false === $checkInList($moduleName, $loadedModules)){
            return null;
        }

        //Level 2 : Path is /module/123 or /module/abc
        if($pathMaxLevel >= 2){
            if(true === is_numeric($pathArray[1])){
                $actionName = 'get';
                $id = $pathArray[1];
            } else {
                $controllerName = $pathArray[1];
            }
        }


        //Level 3 : Path is /module/abc/def or /module/abc/123 or /module/123/abc
        if($pathMaxLevel >= 3 && false === is_numeric($pathArray[1])){
            if(true === is_numeric($pathArray[2])){
                $actionName = 'get';
                $id = $pathArray[2];
            } else {
                $actionName = $pathArray[2];
                $id = $actionName;
            }

            if(isset($pathArray[3]) && false === is_numeric($pathArray[2])) {
                $id = $pathArray[3];
            }
        }

        complete:

        if($moduleName === $moduleNamespace) {
            $controller = ucfirst($moduleName) . '\\Controller\\' . ucfirst($controllerName) . 'Controller';
        } else {
            $controller = ucfirst($moduleName) . '\\' . ucfirst($moduleNamespace) . '\\Controller\\' . ucfirst($controllerName) . 'Controller';
        }

        /*
        p($pathArray);
        p(array(
            'module' => $moduleName,
            'moduleNamespace' => $moduleNamespace,
            'controller' => $controller,    
            'controllerName' => $controllerName,    
            'action' => $actionName,
            'id' => $id,
        ));
        exit;
        */

        if(!$moduleName || !$moduleNamespace || !$controllerName || !$actionName){
            return null;
        }

        return new RouteMatch(array(
            'module' => $moduleName,
            'moduleNamespace' => $moduleNamespace,
            'controller' => $controller,    
            'controllerName' => $controllerName,    
            'action' => $actionName,
            'id' => $id,
        ), strlen($path));
    }


    public function assemble(array $params = array(), array $options = array())
    {
        return $this->route;
    }

    public function getAssembledParams()
    {
        return array();
    }

}
