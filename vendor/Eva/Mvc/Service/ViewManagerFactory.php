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

namespace Eva\Mvc\Service;

use Zend\Console\Console;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\View\Console\ViewManager as ConsoleViewManager;
use Eva\Mvc\View\Http\ViewManager as HttpViewManager;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Service
 */
class ViewManagerFactory implements FactoryInterface
{
    /**
     * Create and return a request instance, according to current environment.
     *
     * @param  ServiceLocatorInterface $serviceLocator 
     * @return \Zend\Stdlib\Message
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if(Console::isConsole()){
            return new ConsoleViewManager();
        }

        return new HttpViewManager();
    }
}
