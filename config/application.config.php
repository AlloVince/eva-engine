<?php
return array(
	'modules' => array(
		'Core',
		'Album',
		'Blog',
	//	'ZendDeveloperTools',
	),
	'protected_module_namespace' => array(
		'Admin',	
	),
	'module_listener_options' => array( 
		'config_cache_enabled' => false,
		'cache_dir'            => EVA_CONFIG_PATH . '/cache',
		'module_paths' => array(
			EVA_MODULE_PATH,
		),
	),
);
