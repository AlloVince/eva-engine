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

namespace Eva\View\Helper;

use Zend\Mvc\Router\RouteStackInterface,
    Zend\Mvc\Router\RouteMatch,
    Zend\View\Exception,
    Eva\Uri\Uri as CoreUri;

/**
 * Render View Partial Cross Module
 * 
 * @category   Eva
 * @package    Eva_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Action extends \Zend\View\Helper\AbstractHelper
{

    public function __invoke($controllerName, $actionName, $params = array())
    {
        $controllerLoader = \Eva\Api::_()->getEvent()->getApplication()->getServiceManager()->get('ControllerLoader');
        $controllerLoader->setInvokableClass($controllerName, $controllerName);
        $controller = $controllerLoader->get($controllerName);
        return $controller->$actionName($params);
    }


}
