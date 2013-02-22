<?php
/**
 * EvaEngine start 
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */




require __DIR__ . '/../init_autoloader.php';
/** Public functions */
function p($r, $usePr = false)
{
    if($usePr || false === method_exists('\Zend\Debug\Debug','dump')){
        echo '<pre>' . print_r($r, true) . '</pre>';
        return;
    }
    \Zend\Debug\Debug::dump($r);
}

$appGlobelConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
$appLocalConfig = EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'local.application.config.php';
if(file_exists($appLocalConfig)){
    $appLocalConfig = include $appLocalConfig;
    $appGlobelConfig = array_merge($appGlobelConfig, $appLocalConfig);
}
