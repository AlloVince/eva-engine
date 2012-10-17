<?php
namespace Activity;

class Module
{
    public function onBootstrap($e)
    {
        $app = $e->getParam('application');
        $serviceManager = $app->getServiceManager();
        $serviceManager->setInvokableClass('Activity\Event\Listener', 'Activity\Event\Listener');
        $app->getEventManager()->attach($serviceManager->get('Activity\Event\Listener'));
    }

    public function getAutoloaderConfig()
    {
        return array(
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
