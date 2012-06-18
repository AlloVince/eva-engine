<?php
namespace Eva\Mvc;

use ArrayObject,
    Zend\Mvc\MvcEvent,
    Zend\Di\Exception\ClassNotFoundException,
    Zend\Di\LocatorInterface,
    Zend\EventManager\EventManagerInterface,
    Zend\EventManager\EventManager,
    Zend\Http\Header\Cookie,
    Zend\Http\PhpEnvironment\Request as PhpHttpRequest,
    Zend\Http\PhpEnvironment\Response as PhpHttpResponse,
    Zend\Stdlib\DispatchableInterface as Dispatchable,
    Zend\Stdlib\ArrayUtils,
    Zend\Stdlib\RequestInterface as Request,
    Zend\Stdlib\ResponseInterface as Response;

class Application extends \Zend\Mvc\Application
{

    /*
    public function dispatch(MvcEvent $e)
    {
        $locator = $this->getLocator();
        if (!$locator) {
            throw new Exception\MissingLocatorException(
                'Cannot dispatch without a locator'
            );
        }

        $routeMatch     = $e->getRouteMatch();
        $moduleName = $routeMatch->getParam('module', 'not-found');
        if(!$moduleName || $moduleName == 'not-found'){
            return parent::dispatch($e);
        }

        $controllerName = $routeMatch->getParam('controller', 'not-found');
        $events         = $this->events();

        //Change controllerName here to full namaspace
        $controllerName = ucfirst($moduleName) . '\\Controller\\' . ucfirst($controllerName) . 'Controller';

        try {
            $controller = $locator->get($controllerName);
        } catch (ClassNotFoundException $exception) {
            $error = clone $e;
            $error->setError(static::ERROR_CONTROLLER_NOT_FOUND)
                  ->setController($controllerName)
                  ->setParam('exception', $exception);

            $results = $events->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $error);
            if (count($results)) {
                $return  = $results->last();
            } else {
                $return = $error->getParams();
            }
            goto complete;
        }

        if ($controller instanceof LocatorAwareInterface) {
            $controller->setLocator($locator);
        }

        if (!$controller instanceof Dispatchable) {
            $error = clone $e;
            $error->setError(static::ERROR_CONTROLLER_INVALID)
                  ->setController($controllerName)
                  ->setControllerClass(get_class($controller));

            $results = $events->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $error);
            if (count($results)) {
                $return  = $results->last();
            } else {
                $return = $error->getParams();
            }
            goto complete;
        }

        $request  = $e->getRequest();
        $response = $this->getResponse();

        //Fix here
        if ($controller instanceof InjectApplicationEventInterface || $moduleName) {
            $controller->setEvent($e);
        }

        try {
            $return   = $controller->dispatch($request, $response);
        } catch (\Exception $ex) {
            $error = clone $e;
            $error->setError(static::ERROR_EXCEPTION)
                  ->setController($controllerName)
                  ->setControllerClass(get_class($controller))
                  ->setParam('exception', $ex);
            $results = $events->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $error);
            if (count($results)) {
                $return  = $results->last();
            } else {
                $return = $error->getParams();
            }
        }

        complete:

        if (!is_object($return)) {
            if (ArrayUtils::hasStringKeys($return)) {
                $return = new ArrayObject($return, ArrayObject::ARRAY_AS_PROPS);
            }
        }
        $e->setResult($return);
        return $return;
    }
     */
}
