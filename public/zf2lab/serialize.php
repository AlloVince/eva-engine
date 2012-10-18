<?php
require_once './autoloader.php';

$config = array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) {
                $config = $sm->get('Configuration');
                if(!isset($config['db'])){
                    return array();
                }
                $adapter = new Zend\Db\Adapter\Adapter($config['db']);
                return $adapter;
            },
        ),
    ),
);
serialize($config);
