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

	/*
    'Di' => array(
        'instance' => array(
            'Zend\Mvc\Router\RouteStackInterface' => array(
                'parameters' => array(
                    'routes' => array(
						'default' => array(
							'type'    => 'Eva\Mvc\Router\Http\ModuleRoute',
							'priority' => 1,
						),
						
                    ),
					'sorted' => true,
                ),
			),
			'Eva\Db\Adapter\Adapter' => array(
				'parameters' => array(
					'driver' => array(
						'driver' => 'Pdo',
						'dsn'            => 'mysql:dbname=eva;hostname=localhost',
						'username'       => 'root',
						'password'       => '123456',
						'driver_options' => array(
							PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
						),
					),
				),
			),
        ),
	),
	 */
);

