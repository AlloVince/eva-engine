<?php
namespace Video;

class Module
{
    public function onBootstrap($e)
    {
        $app = $e->getParam('application');
        $serviceManager = $app->getServiceManager();
        $serviceManager->setInvokableClass('Video\Event\Listener', 'Video\Event\Listener');
        $app->getEventManager()->attach($serviceManager->get('Video\Event\Listener'));
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
