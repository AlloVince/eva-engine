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
            'index/index'   => __DIR__ . '/../view/index/index.phtml',
            'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
        ),
        /*
        'helper_map' => array(
            'uri' => 'Eva\View\Helper\Uri',    
            'link' => 'Eva\View\Helper\Link',
            'formAttr' => 'Eva\Form\View\Helper\FormAttr',
            'input' => 'Eva\Form\View\Helper\Input',
            'label' => 'Eva\Form\View\Helper\Label',
            'widget' => 'Eva\View\Helper\Widget',
            'evajs' => 'Core\Helper\Evajs',
        ),
        */

        'module_namespace_layout_map' => array(
            'Admin' => 'layout/admin'
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
