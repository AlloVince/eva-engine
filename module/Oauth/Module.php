<?php
namespace Oauth;

class Module
{

    public function onBootstrap($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $serviceManager->setInvokableClass('Oauth\Event\Listener', 'Oauth\Event\Listener');
        $e->getApplication()->getEventManager()->attach($serviceManager->get('Oauth\Event\Listener'));
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'ZendOAuth' => __DIR__ . '/../../vendor/ZendOAuth/library/ZendOAuth',
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
