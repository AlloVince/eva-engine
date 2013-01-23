<?php
error_reporting(E_ALL);
// Check version
if( version_compare(phpversion(), '5.3.3', '<') ) {
  printf('PHP 5.3.3 is required, you have %s', phpversion());
  exit(1);
}

defined('EVA_ROOT_PATH')    || define('EVA_ROOT_PATH', __DIR__ . '/../..');
defined('EVA_PUBLIC_PATH')    || define('EVA_PUBLIC_PATH', __DIR__ . '/..');
defined('EVA_LIB_PATH')    || define('EVA_LIB_PATH', __DIR__ . '/../../vendor');
defined('EVA_MODULE_PATH')    || define('EVA_MODULE_PATH', __DIR__ . '/../../module');
defined('EVA_CONFIG_PATH')    || define('EVA_CONFIG_PATH', __DIR__ . '/../../config');

/** Public functions */
function p($r, $usePr = false)
{
    if($usePr || false === method_exists('\Zend\Debug','dump')){
        echo '<pre>' . print_r($r, true) . '</pre>';
        return;
    }
    \Zend\Debug::dump($r);
}

set_include_path(implode(PATH_SEPARATOR, array(
    '.',
    EVA_LIB_PATH,
    //realpath(EVA_PUBLIC_PATH . '/../vendor/'),
    get_include_path(),
)));

//require_once 'Eva/Loader/AutoloaderFactory.php';
require_once EVA_LIB_PATH . '/Zend/library/Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true
    )
));
$loader = Zend\Loader\AutoloaderFactory::getRegisteredAutoloaders();
$loader = $loader[Zend\Loader\AutoloaderFactory::STANDARD_AUTOLOADER];
$loader->registerNamespace('Eva\\', EVA_PUBLIC_PATH . '/../vendor/Eva');
