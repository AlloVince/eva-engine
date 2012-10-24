<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'engine' => __DIR__ . '/../view',
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

            'feed' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/feed[/]',
                    'defaults' => array(
                        'controller' => 'FeedController',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'tweet' => array(
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

            //For test
            'pricing' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pricing[/]',
                    'defaults' => array(
                        'controller' => 'UserController',
                        'action' => 'pricing',
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
            'FeedController' => 'Engine\Controller\FeedController',
        ),
    ),
);
