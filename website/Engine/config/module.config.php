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
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/login[/]',
                    'defaults' => array(
                        'controller' => 'UserController',
                        'action' => 'login',
                    ),
                ),
                'priority' => 2,
            ),
            'autologin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/autologin[/]',
                    'defaults' => array(
                        'controller' => 'UserController',
                        'action' => 'autologin',
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
            'UserController' => 'Engine\Controller\UserController',
        ),
    ),
);
