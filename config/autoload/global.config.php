<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\Mvc\Router\RouteStackInterface' => array(
                'parameters' => array(
                    'routes' => array(
						/*
                        'home' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/',
                                'defaults' => array(
                                    'controller' => 'Core\Controller\CoreController',
                                    'action'     => 'abc',
                                ),
                            ),
						),
						 */
						'default' => array(
							'type'    => 'Eva\Mvc\Router\Http\ModuleRoute',
						),
                    ),
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
);

