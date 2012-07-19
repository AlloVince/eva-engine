<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) {
                $config = $sm->get('Configuration');
                if(!isset($config['db'])){
                    return array();
                }
                if(class_exists('BjyProfiler\Db\Adapter\ProfilingAdapter')){
                    $adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter($config['db']);
                    $adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler);
                    $adapter->injectProfilingStatementPrototype();
                } else {
                    $adapter = new Zend\Db\Adapter\Adapter($config['db']);
                }
                return $adapter;
            },
        ),
    ),
);
