<?php
return array(
    'blog' => array(
        'comment_social' => array(
            'duoshuo' => array(
                'websiteId' => 'avnpc',
            ),
            'denglu' => array(
                'websiteId' => '25799denLkbKLwQl5KiGx3pKvsl4Y6',
            ),
            'disqus' => array(
                'websiteId' => 'avnpc',
            ),
            'youyan' => array(
                'websiteId' => '1500011',
            ),
            'livefyre' => array(
                'websiteId' => '302665',
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'front' => array(
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
            'feed' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/feed[/]',
                    'defaults' => array(
                        'controller' => 'FeedController',
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
                        'controller' => 'PagesController',
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
                        'controller' => 'PagesController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/search[/]',
                    'constraints' => array(
                    ),
                    'defaults' => array(
                        'controller' => 'SearchController',
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
                        'controller' => 'LifeController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
            'thinking' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/thinking[/][:tag][/]',
                    'constraints' => array(
                        'tag'     => '[^/]+',
                    ),
                    'defaults' => array(
                        'controller' => 'IndexController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
                'may_terminate' => true,
                'child_routes' => array(
                    'page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:page]',
                            'constraints' => array(
                                'id' => '[0-9]+'
                            ),
                        ),
                        'may_terminate' => true,
                    ), //conversation end
                )
            ),
            'proxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/proxy[/:id]',
                    'constraints' => array(
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ProxyController',
                        'action' => 'index',
                    ),
                ),
                'priority' => 2,
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'IndexController' => 'Avnpc\Controller\IndexController',
            'PagesController' => 'Avnpc\Controller\PagesController',
            'LifeController' => 'Avnpc\Controller\LifeController',
            'FeedController' => 'Avnpc\Controller\FeedController',
            'ProxyController' => 'Avnpc\Controller\ProxyController',
            'SearchController' => 'Avnpc\Controller\SearchController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'avnpc' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../layout/layout.phtml',
        ),
    ),
);
