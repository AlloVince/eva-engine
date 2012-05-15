<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\Mvc\Router\RouteStackInterface' => array(
                'parameters' => array(
                    'routes' => array(
                        'website' => array(
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
			),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'Engine\Controller\EngineController' => __DIR__ . '/../view',
                    ),
                ),
			),
        ),
    ),
);
