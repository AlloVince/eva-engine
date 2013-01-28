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

    'console' => array(
        'router' => array(
            'routes' => array(
                'queue' => array(
                    'type'    => 'simple',
                    'options' => array(
                        'route'    => 'queue <queueName>',
                        'defaults' => array(
                            'controller' => 'QueueController',
                            'action'     => 'index'
                        )
                    )
                ),
            ),//routes end
        )
    ),

    'queue' => array(
        'enable' => 1,
        'driver' => 'Mongodb',
        'dsn'    => 'mongodb://localhost:27017',
        'default_queues' => array(
            'default',
            'sendmail',
            'sync',
            'failed',
        ),
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

    'datetime' => array(
        'timezone' => 8,
        'format' => 'F j, Y, g:i a', //'Y年m月d日 H:i',
    ),

    'controllers' => array(
        'invokables' => array(
            'ErrorController' => 'Core\Controller\ErrorController',
            'QueueController' => 'Core\Controller\QueueController',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
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
        'assets' => array(
            'phpPath' => '/static/assets/index.php',
            'cachePath' => '/static/assets',
            'cache' => false,
            'merge' => false,
            'compress' => false,
            'version' => '1.0.0',
        ),
        'google_analytics' => array(
            'code' => '',
            'domain' => '',
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout' => EVA_MODULE_PATH . '/Core/layout/layout.phtml',
            'layout/blank' => EVA_MODULE_PATH . '/Core/layout/blank.phtml',
            'layout/admin' => EVA_MODULE_PATH . '/Core/layout/admin.phtml',
            'layout/adminblank' => EVA_MODULE_PATH . '/Core/layout/adminblank.phtml',
            'index/index'   => EVA_MODULE_PATH . '/Core/view/index/index.phtml',
            'error/404'     => EVA_MODULE_PATH . '/Core/view/error/404.phtml',
            'error/index'   => EVA_MODULE_PATH . '/Core/view/error/index.phtml',
            'blank' => EVA_MODULE_PATH . '/Core/view/blank.phtml',
        ),
        'module_namespace_layout_map' => array(
            'Admin' => 'layout/admin'
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'evajs' => 'Core\Helper\Evajs',
            'tagging' => 'Core\Helper\Tagging',
        ),  
    ),

    'superadmin' => array(
        'id' => 1,
        'username' => 'root',
        'password' => '123456',
        'email' => 'allo.vince@gmail.com',
    ),
    
    'dir' => array(

    ),

    'session' => array(

    ),

    'mail' => array(
       'transports' => array(
            'smtp'     => false,
            'sendmail' => true,
            'file'     => false,
        ),
        'di' => array('instance' => array(
            'Zend\Mail\Transport\SmtpOptions' => array(
                'parameters' => array(
                    'name'              => 'sendgrid',
                    'host'              => 'smtp.sendgrid.net',
                    'port' => 25,
                    'connectionClass'  => 'login',
                    'connectionConfig' => array(
                        'username' => 'sendgridusername',
                        'password' => 'sendgridpassword',
                    ),
                )
            ),
            'Eva\Mail\Message' => array(
                'parameters' => array(
                    /*
                    'Zend\Mail\Message::setTo:emailOrAddressList' => 'info@evaengine.com',
                    'Zend\Mail\Message::setTo:name' => 'EvaEngine',
                    'Zend\Mail\Message::setFrom:emailOrAddressList' => 'info@evaengine.com',
                    'Zend\Mail\Message::setFrom:name' => 'EvaEngine',
                    */
                )
            ),
        )),
    ),

    'translator' => array(
        'locale' => 'en',
        'force_locale' => '',  //force_locale will cover locale
        'languages' => array(
            'en', 'zh'
        ),
        'sub_languages' => array(
            //'zh_CN',
            //'en_UK',
        ),
        'auto_switch' => 1,
        'enable_text_domains' => array(
            'admin',
        ),
        'translation_file_patterns' => array(
            'zf' => array(
                'type' => 'PhpArray',
                'base_dir' => EVA_LIB_PATH . '/Zend/resources/languages/',
                'pattern' => '%s/Zend_Validate.php'
            ),
            'main' => array(
                'type' => 'csv',
                'base_dir' => EVA_ROOT_PATH . '/data/languages',
                //'text_domain' => 'admin',
                'pattern' => '%s/main.csv'
            ),
            'admin' => array(
                'type' => 'csv',
                'base_dir' => EVA_ROOT_PATH . '/data/languages',
                'pattern' => '%s/admin.csv'
            )
        ),
        /*
        'translation_files' => array(
            'zh_CN' => array(
                'type' => 'csv',
                'filename' =>  EVA_ROOT_PATH . '/data/languages/zh_CN/admin.csv',
            ),
        ),
        */
        'scaffold' => array(
            'enable' => 1,
            'level' => 2,
            'path' => EVA_ROOT_PATH . '/data/languages/scaffold'
        ),
        /*
        'cache' => array(
            'adapter' => 'memory'
        )
        */
    ),

    'i18n' => array(
        'enable' => 1,
        'admin' => array(
            'enable' => 1,
        ),
    ),

    'authentication' => array(
        'default_adapter' => 'DbTable',
        'default_storage' => 'Session',
    ),

    'cache' => array(
        'enable' => 1,
        'model_cache' => array(
            'enable' => 1,
            'di' => array(
                'definition' => array(
                    'class' => array(
                        'Zend\Cache\Storage\Adapter' => array(
                            'instantiator' => array(
                                'Eva\Cache\StorageFactory',
                                'factory'
                            ),
                        ),
                        'Eva\Cache\StorageFactory' => array(
                            'methods' => array(
                                'factory' => array(
                                    'cfg' => array(
                                        'required' => true,
                                        'type' => false
                                    )
                                )
                            ),
                        ),
                    ),
                ),
                'instance' => array(
                    'Eva\Cache\StorageFactory' => array(
                        'parameters' => array(
                            'cfg' => array(
                                'adapter' => array(
                                    'name' => 'filesystem',
                                    'options' => array(
                                        'cacheDir' => EVA_ROOT_PATH . '/data/cache/model/',
                                    ),
                                ),
                                'plugins' => array('serializer')
                            ),
                        )
                    ),
                )
            ),
        ),
        'page_capture' => array(
            'enable' => 0,
            'page_extension' => 'html',
            'adapter' => 'filesystem', //Could be memcached
            'options' => array(
                'public_dir' => EVA_PUBLIC_PATH . '/static/cache',
            ),
        ),
        /*
        'page_capture' => array(
            'enable' => 1,
            'adapter' => 'memcached', 
            'page_extension' => 'html', 
            'options' => array( 
                'ttl' => 6000,
                'servers' => array(
                    array('127.0.0.1', 11211),
                ),
                'namespace' => null,
                'lib_options' => array(
                    'COMPRESSION' => false
                ),
            ),
        ), 
        */
    ),
);

