<?php

namespace User;

use Core\Auth;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel as PhpViewViewModel;

class Module
{
    public function onBootstrap($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $serviceManager->setInvokableClass('User\Event\Listener', 'User\Event\Listener');
        $e->getApplication()->getEventManager()->attach($serviceManager->get('User\Event\Listener'));

        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_RENDER, array($this, 'setUserToView'), 100);
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

    public function setUserToView($e)
    {
        $user = Auth::getLoginUser();
        $viewModel = $e->getViewModel();
        //Json/Feed ViewModel
        if(!$viewModel->terminate()){
            $viewModel->setVariable('loginUser', $user);
        }
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
            ($controller == 'login' && $action == 'index') || 
            ($controller == 'reset' && $action == 'index')
        ){
            return true;
        }

        $user = Auth::getLoginUser('Auth_Admin');
        if(isset($user['isSuperAdmin'])){
            return true;
        }

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
