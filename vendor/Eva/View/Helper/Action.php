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

use Zend\View\Helper\AbstractHelper,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\View\Exception;

/**
 * Call a Controller action
 * 
 * @category   Eva
 * @package    Eva_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Action extends \Zend\View\Helper\AbstractHelper implements ServiceLocatorAwareInterface
{


    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


    public function __invoke($controllerName, $actionName, $params = array())
    {
        $controllerLoader = $this->serviceLocator->getServiceLocator()->get('ControllerLoader');
        $controllerLoader->setInvokableClass($controllerName, $controllerName);
        $controller = $controllerLoader->get($controllerName);
        return $controller->$actionName($params);
    }


}
