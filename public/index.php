<?php
error_reporting(E_ALL);
// Check version
if( version_compare(phpversion(), '5.3.3', '<') ) {
  printf('PHP 5.3.3 is required, you have %s', phpversion());
  exit(1);
}

defined('EVA_ROOT_PATH')    || define('EVA_ROOT_PATH', __DIR__ . '/..');
defined('EVA_PUBLIC_PATH')    || define('EVA_PUBLIC_PATH', __DIR__);
defined('EVA_MODULE_PATH')    || define('EVA_MODULE_PATH', __DIR__ . '/../module');
defined('EVA_CONFIG_PATH')    || define('EVA_CONFIG_PATH', __DIR__ . '/../config');

/** Public functions */
function p($r)
{
    \Zend\Debug::dump($r);
}

set_include_path(implode(PATH_SEPARATOR, array(
    '.',
    realpath(EVA_PUBLIC_PATH . '/../vendor/'),
    get_include_path(),
)));

require_once 'Eva/Loader/Autoloader.php';
Eva\Loader\Autoloader::factory();
$loader = Eva\Loader\Autoloader::getRegisteredAutoloaders();
$loader = $loader[Eva\Loader\Autoloader::STANDARD_AUTOLOADER];
$loader->registerNamespace('Eva\\', EVA_PUBLIC_PATH . '/../vendor/Eva');

$appConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
\Eva\Registry::set("appConfig", $appConfig);

// setup service manager
$serviceManager = new \Zend\ServiceManager\ServiceManager(new \Zend\Mvc\Service\ServiceManagerConfiguration($appConfig['service_manager']));
$serviceManager->setService('ApplicationConfiguration', $appConfig);
$serviceManager->get('ModuleManager')->loadModules();

//$serviceManager->get('Application')->bootstrap()->run()->send();

$app = $serviceManager->get('Application');
$app->bootstrap()->run()->send();
