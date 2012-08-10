<?php
return array(
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/admin' => __DIR__ . '/../view/layout/admin.phtml',
            'layout/adminindex' => __DIR__ . '/../view/layout/adminindex.phtml',
            'index/index'   => __DIR__ . '/../view/index/index.phtml',
            'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
        ),
        'module_namespace_layout_map' => array(
            'Admin' => 'layout/admin'
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'evajs' => 'Core\Helper\Evajs',
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
        //'transports' => 'file',
    ),

    'translator' => array(
        'locale' => 'zh_CN',
        'force_locale' => '',  //force_locale will cover locale
        'languages' => array(
            'en', 'zh_CN'
        ),
        'auto_switch' => 0,
        'enable_text_domains' => array(
            'admin',
        ),
        'translation_patterns' => array(
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
            'options' => array(
                'public_dir' => EVA_PUBLIC_PATH . '/static/cache/',
            ),
        ),
    ),
);
