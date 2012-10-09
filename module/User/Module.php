<?php

namespace User;

use Core\Auth;

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


    public static function authority($e)
    {
        $router = $e->getRouteMatch();
        $moduleNamespace = $router->getParam('moduleNamespace');
        
        if($moduleNamespace != 'admin'){
            return true;
        }

        $controller = $router->getParam('controllerName');
        $action = $router->getParam('action');
        if( ($controller == 'core' && $action == 'index') || 
            ($controller == 'logout' && $action == 'index') || 
            ($controller == 'login' && $action = 'index') || 
            ($controller == 'reset' && $action = 'index')
        ){
            return true;
        }

        $user = Auth::getLoginUser();
        if(!$user || !isset($user['Roles']) || !in_array('Admin', $user['Roles'])){
            $application = $e->getApplication();
            $event = $application->getEventManager();
            $errorController = 'Core\Admin\Controller\ErrorController';

            $router->setParam('controller', $errorController);
            $router->setParam('action', 'index');
            $controllerLoader = $application->getServiceManager()->get('ControllerLoader');
            $controllerLoader->setInvokableClass($errorController, $errorController);
        }

        return true;
    }

}
