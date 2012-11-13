<?php
return array(
    'module_user' => array(
        'register' => array(
            'repeat_password' => 1,
            'screen_name' => 1,
            'display_invite' => 1,
        ),
        'invite' => array(
            'invite_by' => 'link', // code | link
            'initial_code_amount' => 5,
            'code_expired_time' => 3600*24*10, // 0 is never expire
        ),
        'online_to_offline_time' => 60*15 //15 minites
    ),
);
