<?php
return array(
    'payment' => array(
        'paypal' => array(
            'sandbox' => 0,
            'account' => 'info@shishijia.com',
            'orderTitle' => 'epic test ',
            'currency' => 'USD',
        ),
        'alipay' => array(
            'sandbox' => 0,
            'accountType' => 'create_partner_trade_by_buyer',
            'partnerId' => '200000000',
            'orderTitle' => 'Epic alipay test.',
            'account' => "test@shishijia.com",
            'securityCode' => "xxxxxxxxxxxxxxxxxxxx",
        ),
    ),
    'event' => array(
        'timezone' => array(
            'default' => 8,
        ),
        'status' => array(
            'default' => 'active',
        ),
        'visibility' => array(
            'default' => 'public',
        
        ),
    ),
    'album' => array(
        'visibility' => array(
            'default' => 'public',
        
        ),
    ),
    'group' => array(
        'status' => array(
            'default' => 'active',
        ),
    ),
);
