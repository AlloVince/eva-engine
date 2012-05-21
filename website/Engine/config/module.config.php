<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'engin' => __DIR__ . '/../view',
        ),
	),
	/*
    'router' => array(
        'routes' => array(
            'front' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'controller' => 'Engine\Controller\EngineController',
						'action'     => 'index',
					),
				),
				'priority' => 2,
            ),
        ),
	),
	 */
);
