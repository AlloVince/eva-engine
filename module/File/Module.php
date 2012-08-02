<?php
namespace File;

class Module
{
    public function onBootstrap($e)
    {
        $app = $e->getParam('application');
        $app->getEventManager()->attach('render', array($this, 'registerJsonStrategy'), 100);
    }

    public function registerJsonStrategy($e)
    {
        $matches    = $e->getRouteMatch();
        if(!$matches) {
            return;
        }
        $controller = $matches->getParam('controller');
        //only render on Api controller
        if($controller !== 'File\Api\Controller\FileController'){
            return;
        }

        $app          = $e->getTarget();
        $locator      = $app->getServiceManager();
        $view         = $locator->get('Zend\View\View');
        $jsonStrategy = $locator->get('ViewJsonStrategy');
        $view->getEventManager()->attach($jsonStrategy, 100);
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
