<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'engine' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../layout/layout.phtml',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'IndexController',
                        'action'     => 'index',
                    ),
                ),
                'priority' => 2,
            ),

            'blog' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pages[/]',
                    'defaults' => array(
                        'controller' => 'PagesController',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'post' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/][:id][/]',
                            'constraints' => array(
                                'id' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                'action' => 'get'
                            )
                        )
                    ),
                ),
                'priority' => 2,
            ),

            'register' => array(
                'type' => 'Segment',
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
                'type' => 'Segment',
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

            'logout' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/logout[/]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'LoginController',
                        'action' => 'logout',
                    ),
                ),
                'priority' => 2,
            ),

            'reset' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/reset[/][:action][/]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ResetController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),

            'account' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/account/[:action][/]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'AccountController',
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
            'ResetController' => 'Engine\Controller\ResetController',
            'AccountController' => 'Engine\Controller\AccountController',
        ),
    ),

    'oauth' => array(
        'login_url_path' => '/feed/'
    ),
);
