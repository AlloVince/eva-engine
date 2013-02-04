<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Eva\Mvc\Service;

use Zend\Mvc\Application;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\MvcEvent;
use Eva\Api;

class ApplicationFactory extends \Zend\Mvc\Service\ApplicationFactory
{
    /**
     * Create an EventManager instance
     *
     * Creates a new EventManager instance, seeding it with a shared instance
     * of SharedEventManager.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return EventManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $application = parent::createService($serviceLocator);
        $eventManager = $application->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_BOOTSTRAP, array($this, 'onApplicationBootstrap'), 1000);
        return $application;
    }

    public function onApplicationBootstrap($event)
    {
        Api::_()->setEvent($event->getApplication()->getMvcEvent());
    }
}
