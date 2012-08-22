<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_I18n
 */

namespace Eva\Cache\Service;


use Zend\Di\Di,
    Zend\Di\Config as DiConfig,
    Eva\Config\Config,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Translator.
 *
 * @category   Zend
 * @package    Zend_I18n
 * @subpackage Translator
 */
class ModelCache implements ServiceLocatorAwareInterface
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


    public function __invoke(array $config = array())
    {
        $di = new Di();
        $diConfig = array(
            'definition' => array(
                'class' => array(
                    'Zend\Cache\Storage\Adapter' => array(
                        'instantiator' => array(
                            'Eva\Cache\StorageFactory',
                            'factory'
                        ),
                    ),
                    'Eva\Cache\StorageFactory' => array(
                        'methods' => array(
                            'factory' => array(
                                'cfg' => array(
                                    'required' => true,
                                    'type' => false
                                )
                            )
                        ),
                    ),
                ),
            ),
            'instance' => array(
                'Eva\Cache\StorageFactory' => array(
                    'parameters' => array(
                        'cfg' => array(
                            'adapter' => array(
                                'name' => 'filesystem',
                                'options' => array(
                                    'cacheDir' => EVA_ROOT_PATH . '/data/cache/model/',
                                ),
                            ),
                            'plugins' => array('serializer')
                        ),
                    )
                ),
            )
        );

        $globalConfig = $this->serviceLocator->get('Configuration');
        $globalConfig = isset($globalConfig['cache']['model']) ? $globalConfig['cache']['model'] : array();
        $diConfig = Config::mergeArray($diConfig, $globalConfig, $config);
        $di->configure(new DiConfig($diConfig));
        return $di->get('Eva\Cache\StorageFactory');
    }

}
