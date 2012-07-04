<?php
return array(
    'modules' => array(
        'Core',
        'User',
        'Blog',
        'Engine',
        'Scaffold',
    ),
    'service_manager' => array(
        'use_defaults' => true,
        'services' => array(
            'ViewManager'                  => 'Eva\Mvc\View\ModuleViewManager',
        ),
        'factories'    => array(
            //overwrite and add custom helpers here
            'ViewHelperManager'       => 'Eva\Mvc\Service\ViewHelperManagerFactory',
            //'ControllerLoader'                  => 'Eva\Mvc\Service\ControllerLoaderFactory',
        ),
    ),
    'protected_module_namespace' => array(
        'Admin',    
    ),
    'use_module_template_path_stack' => true,
    'module_listener_options' => array( 
        'config_cache_enabled' => 0,
        'config_glob_paths'    => array(
            EVA_CONFIG_PATH . '/autoload/{,*.}{global,local}.config.php',
        ),
        'cache_dir'            => EVA_CONFIG_PATH . '/cache',
        'module_paths' => array(
            EVA_MODULE_PATH,
            EVA_ROOT_PATH . '/website',
        ),
    ),
);
