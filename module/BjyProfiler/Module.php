<?php

namespace BjyProfiler;

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

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'BjyProfiler\Collector\DbCollector' => function($sm) {
                    $collector = new Collector\DbCollector;
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $collector->setProfiler($adapter->getProfiler());
                    return $collector;
                }
            ),
        );
    }
}
