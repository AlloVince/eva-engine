<?php
return array(
    'di' => array(
        'instance' => array(
			/*
            'alias' => array(
                'blog' => 'Blog\Controller\BlogController',
			),
			 */
            'Blog\Controller\BlogController' => array(
                'parameters' => array(
                    'postTable' => 'Blog\Model\PostTable',
                ),
			),
            'Blog\Model\PostTable' => array(
                'parameters' => array(
                    'adapter' => 'Eva\Db\Adapter\Adapter',
                )
			),

            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(
                        'layout/blog'      => __DIR__ . '/view/layout.phtml',
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'Blog\Controller\BlogController' => __DIR__ . '/../view',
                    ),
                ),
			),
        ),
    ),
);
