<?php
namespace Blog;

class Module
{
    public function onBootstrap($e)
    {
        $app = $e->getParam('application');
        $serviceManager = $app->getServiceManager();
        $serviceManager->setInvokableClass('Blog\Event\Listener', 'Blog\Event\Listener');
        $app->getEventManager()->attach($serviceManager->get('Blog\Event\Listener'));
    }

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
}
