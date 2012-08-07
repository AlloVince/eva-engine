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
    Zend\Http\Request as HttpRequest,
    Zend\Http\PhpEnvironment\Response as HttpResponse,
    Zend\Stdlib\RequestInterface as Request,
    Zend\Stdlib\ResponseInterface as Response,
    Zend\Mvc\MvcEvent;

/**
 * Enable RESTFul style into all controllers
 * Every normal request could be convert to RESTFul request
 * 
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Controller
 */
abstract class RestfulModuleController extends RestfulController
{
    /**
     * Handle the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException if no route matches in event or invalid HTTP method
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $request = $e->getRequest();
        $action  = $routeMatch->getParam('action', false);

        //EvaEngine : Default route will certainly have action param
        $method  = '';
        if($action){
            $method = static::getMethodFromAction($action);
        }

        if ($action && method_exists($this, $method)) {
            $return = $this->$method();
        } else {
            // RESTful methods
            switch (strtolower($request->getMethod())) {
                case 'get':
                    if (null !== $id = $routeMatch->getParam('id')) {
                        $action = 'get';
                        $return = $this->get($id);
                        break;
                    }
                    if (null !== $id = $request->getQuery()->get('id')) {
                        $action = 'get';
                        $return = $this->get($id);
                        break;
                    }
                    $action = 'getList';
                    $return = $this->getList();
                    break;
                case 'post':
                    $action = 'create';
                    $return = $this->processPostData($request);
                    break;
                case 'put':
                    $action = 'update';
                    $return = $this->processPutData($request, $routeMatch);
                    break;
                case 'delete':
                    if (null === $id = $routeMatch->getParam('id')) {
                        if (!($id = $request->getQuery()->get('id', false))) {
                            throw new Exception\DomainException('Missing identifier');
                        }
                    }
                    $action = 'delete';
                    $return = $this->delete($id);
                    break;
                default:
                    throw new Exception\DomainException('Invalid HTTP method!');
            }

            $routeMatch->setParam('action', $action);
        }

        // Emit post-dispatch signal, passing:
        // - return from method, request, response
        // If a listener returns a response object, return it immediately
        $e->setResult($return);

        return $return;
    }
}
