<?php
return array(
    'payment' => array(
        'return_url_path' => '/payment/response',
        'cancel_url_path' => '/payment/cancel',
    ),
    'view_manager' => array(
        'template_map' => array(
            'payment/request/index' => __DIR__ . '/../view/request/index.phtml',
            'payment/response/index' => __DIR__ . '/../view/response/index.phtml',
        ),
    ),
);
