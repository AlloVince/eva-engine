<?php
return array(
    'contacts' => array(
        'import_url_path' => '/contacts/import/',
    ),
    'cache' => array(
        'contacts_import' => array(
            'enable' => 1,
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'cacheDir' => EVA_ROOT_PATH . '/data/cache/other/',
                    'ttl' => 6000,
                ),
            ),
            'plugins' => array('serializer')
        ),
    ),
);
