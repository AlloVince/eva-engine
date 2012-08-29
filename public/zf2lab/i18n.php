<?php
    require_once './autoloader.php';

    $translator = Zend\I18n\Translator\Translator::factory(array(
        'locale' => 'jp',
        'translation_patterns' => array(
            array(
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/_files',
                'pattern' => 'translation-%s.php'
            )
        ),
        /*
        'translation_files' => array(
            array(
                'type' => 'phparray',
                'filename' =>  __DIR__ . '/_files/translation_en.php',
            )
        ),
        */
        /*
        'cache' => array(
            'adapter' => 'memory'
        )
        */
    ));
    echo $translator->translate('Dream');
