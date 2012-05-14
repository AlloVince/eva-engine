<?php
error_reporting(E_ALL);
// Check version
if( version_compare(phpversion(), '5.3.0', '<') ) {
  printf('PHP 5.3.0 is required, you have %s', phpversion());
  exit(1);
}

defined('EVA_PUBLIC_PATH')	|| define('EVA_PUBLIC_PATH', __DIR__);
defined('EVA_MODULE_PATH')	|| define('EVA_MODULE_PATH', __DIR__ . '/../module');
defined('EVA_CONFIG_PATH')	|| define('EVA_CONFIG_PATH', __DIR__ . '/../config');

/** Public functions */
function p($r)
{
	echo "<pre>";
	//print_r($r);
	\Zend\Debug::dump($r);
    echo "</pre>";
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

$listenerOptions  = new Eva\Module\Listener\ListenerOptions($appConfig['module_listener_options']);
$defaultListeners = new Eva\Module\Listener\DefaultListenerAggregate($listenerOptions);
$defaultListeners->getConfigListener()->addConfigGlobPath(EVA_CONFIG_PATH . '/autoload/*.config.php');

$moduleManager = new Eva\Module\Manager($appConfig['modules']);
$moduleManager->events()->attachAggregate($defaultListeners);
$moduleManager->loadModules();

$config = $defaultListeners->getConfigListener()->getMergedConfig();
//\Eva\Registry::set("config", $config->toArray());


/*
$router = new \Zend\Mvc\Router\Http\TreeRouteStack();
$route = new \Eva\Mvc\Router\Http\Restful('/abc');
$router->addRoute(
	'restful',
	$route,
	$priority = null
);
 */
/*
$request = new \Zend\Http\Request();
$router = \Zend\Mvc\Router\Http\TreeRouteStack::factory(array(
	'routes' => array(
		'home' => array(
			'type' => '\Zend\Mvc\Router\Http\Literal',
			'options' => array(
				'route'    => '/',
				'defaults' => array(
					'controller' => 'Core\Controller\IndexController',
					'action'     => 'index',
				),
			),
		),
		'restful' => array(
			'type'    => '\Zend\Mvc\Router\Http\Regex',
			'options' => array(
				//'route'       => '/:controller[.:formatter][/:id]',
				'regex' => '/(?<controller>[a-zA-Z0-9]+)/(?<id>[a-zA-Z0-9_-]+)(\.(?<format>(json|html|xml|rss)))?',
				'constraints' => array(
					'controller' => '(^admin[a-zA-Z0-9_-]*)',
					'formatter'  => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id'         => '[a-zA-Z0-9_-]*'
				),
				'spec' => '/%controller%/%id%.%format%',
			),
		),	
	)
));
$url = '/abc';
$request->setUri($url);
p($router->match($request));

$url = '/admin/001-some-blog_slug-here.html';
$request->setUri($url);
p($router->match($request));
exit;
*/





// Create application, bootstrap, and run
$bootstrap   = new Eva\Mvc\Bootstrap($config);
$application = new Eva\Mvc\Application;

/*
$application->events()->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, function($e){
	p(1);
});
 */
$bootstrap->bootstrap($application);
$application->run()->send();
