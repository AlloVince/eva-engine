<?php
return array(
    /*
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    */

    'upload' => array(
        'storage' => array(
            'default' => array(
                'rootpath' => EVA_PUBLIC_PATH . '/static/upload',
                'pathlevel' => 3, 
                'urlroot' => EVA_PUBLIC_PATH,
                'domain' => '',
                'thumburl' => 'http://s.zf2.com/thumb/',
            ),
        ),
    ),
);
