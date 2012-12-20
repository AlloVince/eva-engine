<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Core;

use ZendQueue\Queue as ZendQueue;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Core Session
 *
 * @category   Core
 * @package    Core_Session
 */
class Queue extends ZendQueue
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

    public static function factory($queueName)
    {
    
    }

}
