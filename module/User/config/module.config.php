<?php
return array(
    'permission' => array(
        'admin' => array(
            'SUPER_ADMIN',
            'ADMIN',
        )
    ),
    'module_user' => array(
        'events' => array(
        
        ),
        'register' => array(
            'repeat_password' => 1,
            'screen_name' => 1,
            'display_invite' => 1,
            'invite_required' => 0,
            'default_role' => 'USER',
        ),
        'invite' => array(
            'invite_by' => 'link', // code | link
            'initial_code_amount' => 5,
            'code_expired_time' => 3600*24*10, // 0 is never expire
        ),
        'reset' => array(
            'path' => '/reset/',
            'code_expired_time' => 0,
        ),
        'verify' => array(
            'verify_email_code_expried_time' => 0,
        ),
        'online_to_offline_time' => 60*15 //15 minites
    ),
);
