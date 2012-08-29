<?php
    require_once './autoloader.php';

    $capture = \Zend\Cache\PatternFactory::factory('capture', array(
        'public_dir' => __DIR__,
    ));

    $pageId = 'test.html';
    $capture->start($pageId);

