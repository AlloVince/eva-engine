<?php
namespace Core;

use Eva\ModuleManager\ModuleManager,
    Eva\EventManager\StaticEventManager,
    Eva\ModuleManager\Feature\AutoloaderProvider;

class Module
{
	/*
    public function init(ModuleManager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
	}
	 */

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
		\Eva\Api::_()->setEvent($e);
	}

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'db-adapter' =>  function($sm) {
					$config = $sm->get('config');
					$config = $config['db'];
                    $dbAdapter = new \Zend\Db\Adapter\Adapter($config);

					//\Eva\Registry::set("dbAdapter", $dbAdapter);
                    return $dbAdapter;
                },
            ),
        );
	}
    
	/*
    public function initializeView($e)
    {
        $app          = $e->getParam('application');
        $basePath     = $app->getRequest()->getBasePath();
        $locator      = $app->getLocator();
        $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
        $renderer->plugin('url')->setRouter($app->getRouter());
        $renderer->plugin('basePath')->setBasePath($basePath);
	}
	 */
}
