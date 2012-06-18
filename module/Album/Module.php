<?php

namespace Album;

use Zend\Form\View\HelperLoader as FormHelperLoader;
use Album\Model\AlbumTable;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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
        $application        = $e->getParam('application');
        $sharedEventManager = $application->events()->getSharedManager();
        $sharedEventManager->attach('Album', 'dispatch', array($this, 'onAlbumDispatched'), 2);
        // (change 2 to -2 if you want the listener to trigger before the action is dispatched)
    }

    public function onAlbumDispatched($e)
    {
        // This is only called if a controller within our module has been dispatched
        $application    = $e->getParam('application');
        $serviceManager = $application->getServiceManager();
        $helperLoader   = $serviceManager->get('Zend\View\HelperLoader');

        $helperLoader->registerPlugins(new FormHelperLoader());
    }

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'album-table' =>  function($sm) {
                    $dbAdapter = $sm->get('db-adapter');
                    $table = new AlbumTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }
}
