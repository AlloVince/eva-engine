<?php
namespace Core;

use Eva\Api;
use Zend\Mvc\MvcEvent;

class Module
{
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

    public function onBootstrap($e)
    {
        Api::_()->setEvent($e);

        if(!Api::_()->isModuleLoaded('User')){
            $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, array($this, 'authority'), 100);
        } else {
            $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, array('User\Module', 'authority'), 100);
        }
    }

    public function authority($e)
    {
        $router = $e->getRouteMatch();
        $moduleNamespace = $router->getParam('moduleNamespace');
        if($moduleNamespace != 'admin'){
            return false;
        }

        $controller = $router->getParam('controllerName');
        $action = $router->getParam('action');

        //No authority pages : login form | login post | logout
        //TODO : add no authority page to config file
        if($controller == 'core' && $action == 'index' || $controller == 'logout' && $action == 'index' || $controller == 'login' && $action = 'index'){
            return;
        }
        $auth = new Auth();
        $isAuthed = $auth->getStorage()->read();
        if(!$isAuthed){
            $application = $e->getApplication();
            $event = $application->getEventManager();
            $errorController = 'Core\Admin\Controller\ErrorController';

            $router->setParam('controller', $errorController);
            $router->setParam('action', 'index');
            $controllerLoader = $application->getServiceManager()->get('ControllerLoader');
            $controllerLoader->setInvokableClass($errorController, $errorController);

            /*
            $e->setError(\Core\Admin\Controller\ErrorController::ERROR_UNAUTHORIZED)
            ->setController($errorController)
            ->setControllerClass($errorController);
            */
            //$results = $event->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);


            /*
            throw new Exception\UnauthorizedException(printf(
                'Unauthorized admin resource'
            ));
            */
        }
    }
}
