<?php
return array(
    'libRootPath' => __DIR__ . '/../lib',
    'urlRootPath' => __DIR__,
    'headers' => array('Access-Control-Allow-Origin: *'),
    'cache' => false,
    'loaderJs' => '',
    'seajsEnable' => true,
    'filters' => array(
        'lessNodeBin' => '',
        'lessNodeModules' => '',
    ),
    'defines' => array(
    
    ),
    'moduleMap' => array(
        'zenddevelopertools'  => 'ZendDeveloperTools',
    ),
);
