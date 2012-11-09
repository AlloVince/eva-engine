<?php
return array(
    'payment' => array(
        'request_url_path' => '/payment/request',
        'return_url_path'  => '/payment/response',
        'cancel_url_path'  => '/payment/cancel',
        'paymentSecretKey' => '',
    ),
    'view_manager' => array(
        'template_map' => array(
            'payment/request/index' => __DIR__ . '/../view/request/index.phtml',
            'payment/response/index' => __DIR__ . '/../view/response/index.phtml',
        ),
    ),
);
