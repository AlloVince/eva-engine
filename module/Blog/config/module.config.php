<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'blog' => __DIR__ . '/../view',
                    ),
                ),
            ),
        ),
    ),
);

