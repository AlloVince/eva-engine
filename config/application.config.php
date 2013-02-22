<?php
return array(
    'modules' => array(
        'Core',
        'Blog',
        'File',
        'Album',
        'User',
        'Event',
        'Activity',
        'Message',
        'Scaffold',
        'Oauth',
        'Contacts',
        'Payment',
        'Video',
        'Group',
        'Crontab',
        'Webservice',
        'Engine',
        //'ZendDeveloperTools',
        //'BjyProfiler',
        //'Doctrine',
        //'DoctrineModule',
        //'DoctrineORMModule',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => false,
        'config_cache_key' => 'module-config-cache',
        'config_glob_paths'    => array(
            EVA_CONFIG_PATH . '/autoload/{,*.}{global,local}.config.php',
            EVA_CONFIG_PATH . '/local.all.config.php',
        ),
        'cache_dir'            => EVA_ROOT_PATH . '/data/cache/config',
        'module_paths' => array(
            EVA_ROOT_PATH . '/depends',
            EVA_MODULE_PATH,
            EVA_ROOT_PATH . '/website',
        ),
        'module_map_cache_enabled' => false,
        'module_map_cache_key' => 'module-map-cache',
        'check_dependencies' => true,
    ),

    'service_manager' => array(
        'factories'    => array(
            'ServiceListener' => 'Eva\Mvc\Service\ServiceListenerFactory',
            'translator' => 'Eva\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'protected_module_namespace' => array(
        'Admin', 'Api'   
    ),
);
