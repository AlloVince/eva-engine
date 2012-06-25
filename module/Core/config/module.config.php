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
        'helper_map' => array(
            'uri' => 'Eva\View\Helper\Uri',    
            'link' => 'Eva\View\Helper\Link',
            'formAttr' => 'Eva\Form\View\Helper\FormAttr',
            'input' => 'Eva\Form\View\Helper\Input',
            'widget' => 'Eva\View\Helper\Widget',
        ),

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
        ),
        'static_cache' => array(
            'enable' => 0,
        ),
    ),
);
