<?php
/**
 * EvaEngine start 
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */
error_reporting(E_ALL | E_STRICT);

// Check php version
if( version_compare(phpversion(), '5.3.3', '<') ) {
  printf('PHP 5.3.3 is required, you have %s', phpversion());
  exit(1);
}

defined('EVA_ROOT_PATH')    || define('EVA_ROOT_PATH', __DIR__ . '/..');
defined('EVA_PUBLIC_PATH')    || define('EVA_PUBLIC_PATH', __DIR__);
defined('EVA_LIB_PATH')    || define('EVA_LIB_PATH', __DIR__ . '/../vendor');
defined('EVA_MODULE_PATH')    || define('EVA_MODULE_PATH', __DIR__ . '/../module');
defined('EVA_CONFIG_PATH')    || define('EVA_CONFIG_PATH', __DIR__ . '/../config');
//For ZendDeveloperTools
define('REQUEST_MICROTIME', microtime(true));

/** Public functions */
function p($r, $usePr = false)
{
    if($usePr || false === method_exists('\Zend\Debug\Debug','dump')){
        echo '<pre>' . print_r($r, true) . '</pre>';
        return;
    }
    \Zend\Debug\Debug::dump($r);
}

require_once EVA_LIB_PATH . '/Zend/library/Zend/Loader/AutoloaderFactory.php';
use Zend\Loader\AutoloaderFactory;
AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true
    )
));
$loader = AutoloaderFactory::getRegisteredAutoloaders();
$loader = $loader[AutoloaderFactory::STANDARD_AUTOLOADER];
$loader->registerNamespace('Eva\\', EVA_LIB_PATH . '/Eva');

$appGlobelConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
$appLocalConfig = EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'local.application.config.php';
if(file_exists($appLocalConfig)){
    $appLocalConfig = include $appLocalConfig;
    $appGlobelConfig = array_merge($appGlobelConfig, $appLocalConfig);
}
