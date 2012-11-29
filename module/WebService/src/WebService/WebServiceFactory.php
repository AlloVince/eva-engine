<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace WebServiceFactory;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   Zend
 * @package    Oauth
 */
class WebServiceFactory
{
    protected $options;

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    protected $adapter;

    /**
    * Constructor
    *
    */
    public static function factory($serviceName, array $options, ServiceLocatorInterface $serviceLocator)
    {
        $webservice = new static();

        if(false == class_exists($adapterClass)){
            throw new Exception\InvalidArgumentException(sprintf('WebService %s not exist', $serviceName));
        }

        $webservice->setServiceLocator($serviceLocator);
        $webservice->setOptions($options);
        $webservice->initAdapter($serviceName);

        return $webservice;
    }



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

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function initAdapter($serviceName)
    {
        $options = $this->getOptions();
        if(false === strpos($serviceName, '\\')){
            $adapterClass = "Adapter\\$serviceName'"
        } else {
            $adapterClass = $serviceName;
        }

        $adapter = new $adapterClass();
        $adapter->setOptions($options);
        if($this->serviceLocator){
            $adapter->setServiceLocator($this->serviceLocator);
        }

        return $this->adapter = $adapter;
    }

    /**
    * Returns the authentication adapter
    *
    * The adapter does not have a default if the storage adapter has not been set.
    *
    * @return Adapter\AdapterInterface|null
    */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
    * Sets the authentication adapter
    *
    * @param  Adapter\AdapterInterface $adapter
    * @return AuthenticationService Provides a fluent interface
    */
    public function setAdapter(Adapter\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }
}
