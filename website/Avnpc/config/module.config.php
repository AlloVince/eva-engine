<?php
return array(
    'router' => array(
        'routes' => array(
            'front' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Avnpc\Controller\IndexController',
                        'action'     => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'feed' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/feed[/]',
                    'defaults' => array(
                        'controller' => 'Avnpc\Controller\FeedController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'frontp' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/p[/][:id][/]',
                    'constraints' => array(
                        'id'     => '\d+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avnpc\Controller\PagesController',
                        'action' => 'get',
                    ),
                ),
                'priority' => 2,
            ),
            'frontposts' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/pages[/][:id][/]',
                    'constraints' => array(
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avnpc\Controller\PagesController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'frontlife' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/life[/][:id][/]',
                    'constraints' => array(
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avnpc\Controller\LifeController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'thinking' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/thinking[/][:page][/]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avnpc\Controller\IndexController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'proxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/proxy[/:id]',
                    'constraints' => array(
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avnpc\Controller\ProxyController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Avnpc\Controller\IndexController' => 'Avnpc\Controller\IndexController',
            'Avnpc\Controller\PagesController' => 'Avnpc\Controller\PagesController',
            'Avnpc\Controller\LifeController' => 'Avnpc\Controller\LifeController',
            'Avnpc\Controller\FeedController' => 'Avnpc\Controller\FeedController',
            'Avnpc\Controller\ProxyController' => 'Avnpc\Controller\ProxyController',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'blank' => __DIR__ . '/../view/blank.phtml',
            'layout/layout' => __DIR__ . '/../layout/avnpc.phtml',
            'avnpc/index' => __DIR__ . '/../view/index.phtml',
            'avnpc/pages/get' => __DIR__ . '/../view/pages/get.phtml',
            'avnpc/life/index' => __DIR__ . '/../view/life/index.phtml',
            'avnpc/feed' => __DIR__ . '/../view/feed.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
            'error/404'   => __DIR__ . '/../view/error/index.phtml',
        ),
    ),
);
