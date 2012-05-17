<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'Blog\Controller\BlogController' => __DIR__ . '/../view',
                        'Blog\Admin\Controller\BlogController' => __DIR__ . '/../view/admin',
                    ),
                ),
            ),
        ),
	),
);

