<?php
namespace Album;

class Module
{
    public function onBootstrap($e)
    {
        $app = $e->getParam('application');
        $serviceManager = $app->getServiceManager();
        $serviceManager->setInvokableClass('Album\Event\Listener', 'Album\Event\Listener');
        $app->getEventManager()->attach($serviceManager->get('Album\Event\Listener'));
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
