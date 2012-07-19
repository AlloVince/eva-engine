<?php
return array(
    'router' => array(
        'routes' => array(
            'default' => array(
                'type'    => 'Eva\Mvc\Router\Http\ModuleRoute',
                'priority' => 1,
            ),
        ),
        'sorted' => true,
    ),
    'db' => array(
        'driver' => 'Pdo',
        'dsn'            => 'mysql:dbname=eva;hostname=localhost',
        'username'       => 'root',
        'password'       => '123456',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'prefix' => 'eva_',
    ),

    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            //'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'site' => array(
        'uri' => array(
            'callbackName' => 'callback',
        ),
        'link' => array(
            //'host' => 'abc.com',
            'basePath' => '/static',
            'versionName' => 'v',
            'version' => '1.0.0',
        ),
    ),
);
