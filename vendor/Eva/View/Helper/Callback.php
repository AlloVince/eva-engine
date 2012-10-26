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
* Render View Partial Cross Module
* 
* @category   Eva
* @package    Eva_View
* @subpackage Helper
* @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
* @license    http://framework.zend.com/license/new-bsd     New BSD License
*/
class Callback extends AbstractHelper implements ServiceLocatorAwareInterface
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


    public function __invoke($callbackUrl = null, $paramName = 'callback')
    {
        if(!$callbackUrl) {
            $view = $this->view;
            $callbackUrl = $view->serverUrl();
        }
        $requestUri = $this->serviceLocator->getServiceLocator()->get('Request')->getServer()->get('REQUEST_URI');
        return '<input name="' . $paramName . '" type="hidden" value="' . $callbackUrl . $requestUri . '">';
    }
}
