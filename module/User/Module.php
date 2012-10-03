<?php
namespace User;

class Module
{
    public function onBootstrap($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $serviceManager->setInvokableClass('User\Event\Listener', 'User\Event\Listener');
        $e->getApplication()->getEventManager()->attach($serviceManager->get('User\Event\Listener'));
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


    public static function authority($event)
    {
        $router = $event->getRouteMatch();
        $moduleNamespace = $router->getParam('moduleNamespace');
        
        if($moduleNamespace != 'admin'){
            return true;
        }

        $controller = $router->getParam('controllerName');
        $action = $router->getParam('action');
        if($controller == 'core' && $action == 'index' || 
            $controller == 'logout' && $action == 'index' || 
            $controller == 'login' && $action = 'index' || 
            $controller == 'reset' && $action = 'index'){
            return true;
        }

        return true;
    }

}
