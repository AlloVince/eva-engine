<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'engine' => __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/blog[/]',
                    'defaults' => array(
                        'controller' => 'PagesController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'pages' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/pages[/:id]',
                    'constraints' => array(
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'PagesController',
                        'action' => 'get',
                    ),
                ),
                'priority' => 2,
            ),
            'index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'IndexController',
                        'action'     => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'register' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/register[/]',
                    'defaults' => array(
                        'controller' => 'UserController',
                        'action' => 'register',
                    ),
                ),
                'priority' => 2,
            ),
            'pricing' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/pricing[/]',
                    'defaults' => array(
                        'controller' => 'UserController',
                        'action' => 'pricing',
                    ),
                ),
                'priority' => 2,
            ),
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/login/[:action/]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'LoginController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'PagesController' => 'Engine\Controller\PagesController',
            'IndexController' => 'Engine\Controller\IndexController',
            'LoginController' => 'Engine\Controller\LoginController',
            'UserController' => 'Engine\Controller\UserController',
        ),
    ),
);
