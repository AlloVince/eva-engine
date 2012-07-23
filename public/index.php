<?php
error_reporting(E_ALL);
// Check version
if( version_compare(phpversion(), '5.3.3', '<') ) {
  printf('PHP 5.3.3 is required, you have %s', phpversion());
  exit(1);
}

defined('EVA_ROOT_PATH')    || define('EVA_ROOT_PATH', __DIR__ . '/..');
defined('EVA_PUBLIC_PATH')    || define('EVA_PUBLIC_PATH', __DIR__);
defined('EVA_LIB_PATH')    || define('EVA_LIB_PATH', __DIR__ . '/../vendor');
defined('EVA_MODULE_PATH')    || define('EVA_MODULE_PATH', __DIR__ . '/../module');
defined('EVA_CONFIG_PATH')    || define('EVA_CONFIG_PATH', __DIR__ . '/../config');

define('REQUEST_MICROTIME', microtime(true));

/** Public functions */
function p($r)
{
    \Zend\Debug::dump($r);
}

/*
set_include_path(implode(PATH_SEPARATOR, array(
    '.',
    EVA_LIB_PATH,
    get_include_path(),
)));
*/

//require_once 'Eva/Loader/AutoloaderFactory.php';
require_once EVA_LIB_PATH . '/Zend/library/Zend/Loader/AutoloaderFactory.php';
require_once EVA_LIB_PATH . '/Eva/Loader/AutoloaderFactory.php';
Eva\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true
    )
));
$loader = Eva\Loader\AutoloaderFactory::getRegisteredAutoloaders();
$loader = $loader[Eva\Loader\AutoloaderFactory::STANDARD_AUTOLOADER];
$loader->registerNamespace('Eva\\', EVA_LIB_PATH . '/Eva');

$appConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
//\Eva\Registry::set("appConfig", $appConfig);

Zend\Mvc\Application::init($appConfig)->run()->send();

/*
// setup service manager
$serviceManager = new \Zend\ServiceManager\ServiceManager(new \Zend\Mvc\Service\ServiceManagerConfiguration($appConfig['service_manager']));
$serviceManager->setService('ApplicationConfiguration', $appConfig);
$moduleManger = $serviceManager->get('ModuleManager');
$moduleManger->loadModules();

//$serviceManager->get('Application')->bootstrap()->run()->send();

$app = $serviceManager->get('Application');
$app->bootstrap()->run()->send();
*/
