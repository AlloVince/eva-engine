<?php
namespace WebService;

class Module
{

    public function onBootstrap($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $serviceManager->setInvokableClass('WebService\Event\Listener', 'WebService\Event\Listener');
        $e->getApplication()->getEventManager()->attach($serviceManager->get('WebService\Event\Listener'));
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
