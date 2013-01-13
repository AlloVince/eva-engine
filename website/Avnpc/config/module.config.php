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
                        'page'     => '[^/]+',
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

            'group' => array(
                'type' => 'Segment',
                'may_terminate' => true,
                'priority' => 2,
                'options' => array(
                    'route' => '/groups[/]',
                    'defaults' => array(
                        'controller' => 'GroupController',
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(
                    'action' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:action][/]',
                            'constraints' => array(
                                'action' => '[a-zA-Z]+',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'group_id' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:group_id][/]',
                            'constraints' => array(
                                'group_id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'get',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'sub' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '[:controller][/]',
                                    'constraints' => array(
                                        'controller' => 'blog|event|album',
                                    ),
                                    'defaults' => array(
                                        'action' => 'groupIndex',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'id' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '[:id][/]',
                                            'constraints' => array(
                                                'id' => '[0-9]+',
                                            ),
                                            'defaults' => array(
                                                'action' => 'groupSingle',
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes' => array(
                                            'edit' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '[edit][/]',
                                                    'defaults' => array(
                                                        'action' => 'groupEdit',
                                                    ),
                                                ),
                                                'may_terminate' => true,
                                            ), //group sub sub child : edit
                                        ), //group sub sub children
                                    ), //group sub sub child : id
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '[create][/]',
                                            'defaults' => array(
                                                'action' => 'groupCreate',
                                            ),
                                        ),
                                        'may_terminate' => true,
                                    ), //group sub sub child : id
                                ), //group sub sub children
                            ), //group sub child : controller
                        ), //group children
                    ), //group child action : group_id
                ), //group children
            ), //group

        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Blog' => 'Avnpc\Controller\IndexController',
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
