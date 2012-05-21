<?php
return array(
    'router' => array(
        'routes' => array(
            'default' => array(
				'type'    => 'Eva\Mvc\Router\Http\ModuleRoute',
				'priority' => 1,
            ),
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
    ),
);
