<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * This autoloading setup is really more complicated than it needs to be for most
 * applications. The added complexity is simply to reduce the time it takes for
 * new developers to be productive with a fresh skeleton. It allows autoloading
 * to be correctly configured, regardless of the installation method and keeps
 * the use of composer completely optional. This setup should work fine for
 * most users, however, feel free to configure autoloading however you'd like.
 */

error_reporting(E_ALL | E_STRICT);

// Check php version
if( version_compare(phpversion(), '5.3.3', '<') ) {
  printf('PHP 5.3.3 is required, you have %s', phpversion());
  exit(1);
}

defined('EVA_ROOT_PATH')    || define('EVA_ROOT_PATH', __DIR__);
defined('EVA_PUBLIC_PATH')    || define('EVA_PUBLIC_PATH', __DIR__ . '/public');
defined('EVA_LIB_PATH')    || define('EVA_LIB_PATH', __DIR__ . '/vendor');
defined('EVA_MODULE_PATH')    || define('EVA_MODULE_PATH', __DIR__ . '/module');
defined('EVA_CONFIG_PATH')    || define('EVA_CONFIG_PATH', __DIR__ . '/config');
//For ZendDeveloperTools
define('REQUEST_MICROTIME', microtime(true));

// Composer autoloading
if (file_exists(EVA_LIB_PATH . '/autoload.php')) {
    $loader = include EVA_LIB_PATH . '/autoload.php';
}

$zf2Path = false;
if (getenv('ZF2_PATH')) {           // Support for ZF2_PATH environment variable or git submodule
    $zf2Path = getenv('ZF2_PATH');
} elseif (get_cfg_var('zf2_path')) { // Support for zf2_path directive value
    $zf2Path = get_cfg_var('zf2_path');
} elseif (is_dir(EVA_LIB_PATH . '/Zend/library')) {
    $zf2Path = EVA_LIB_PATH . '/Zend/library';
}

if ($zf2Path) {
    if (isset($loader)) {
        $loader->add('Zend', $zf2Path);
        $loader->add('Eva', EVA_LIB_PATH . '/Eva');
    } else {
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                    'Eva' => EVA_LIB_PATH . '/Eva',
                ),
            )
        ));
        $loader = Zend\Loader\AutoloaderFactory::getRegisteredAutoloaders();
    }
}


if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}
