<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Eva\Mvc\View;


use Traversable;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\View\HelperBroker as ViewHelperBroker;
use Zend\View\HelperLoader as ViewHelperLoader;
use Zend\View\Renderer\PhpRenderer as ViewPhpRenderer;
use Zend\View\Resolver as ViewResolver;
use Zend\View\Strategy\PhpRendererStrategy;
use Zend\View\View;

/**
 * Eva Mvc View Bootstrap Manager
 * 1. Register  Eva\Mvc\View\InjectTemplateListener on bootstrap
 * 2. Add controller alias automatic when route is Eva default router
 * 3. Reset view root path by modules
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage View
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ModuleViewManager extends \Zend\Mvc\View\ViewManager
{

    protected $viewRootPath;

    /**
     * Prepares the view layer
     * 
     * @param  ApplicationInterface $application 
     * @return void
     */
    public function onBootstrap($event)
    {
        $application  = $event->getApplication();
        $services     = $application->getServiceManager();
        $config       = $services->get('Configuration');
        $events       = $application->getEventManager();
        $sharedEvents = $events->getSharedManager();

        //Fixed config instanceof here
        $this->config   = isset($config['view_manager']) && (is_array($config['view_manager']) || $config['view_manager'] instanceof ArrayAccess)
        //$this->config   = isset($config['view_manager']) && (is_array($config['view_manager']) || $config['view_manager'] instanceof \Zend\Config\Config)
                        ? $config['view_manager'] 
                        : array();
        $this->services = $services;
        $this->event    = $event;

        $routeNotFoundStrategy   = $this->getRouteNotFoundStrategy();
        $exceptionStrategy       = $this->getExceptionStrategy();
        $mvcRenderingStrategy    = $this->getMvcRenderingStrategy();
        $createViewModelListener = new \Zend\Mvc\View\CreateViewModelListener();
        $injectTemplateListener  = new \Eva\Mvc\View\InjectTemplateListener();
        $injectViewModelListener = new \Zend\Mvc\View\InjectViewModelListener();

        $this->registerMvcRenderingStrategies($events);
        $this->registerViewStrategies();

        $events->attach($routeNotFoundStrategy);
        $events->attach($exceptionStrategy);
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($injectViewModelListener, 'injectViewModel'), -100);
        $events->attach($mvcRenderingStrategy);
        
        $sharedEvents->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($createViewModelListener, 'createViewModelFromArray'), -80);
        $sharedEvents->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($routeNotFoundStrategy, 'prepareNotFoundViewModel'), -90);
        $sharedEvents->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($createViewModelListener, 'createViewModelFromNull'), -80);
        $sharedEvents->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($injectTemplateListener, 'injectTemplate'), -90);
        $sharedEvents->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($injectViewModelListener, 'injectViewModel'), -100);
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('bootstrap', array($this, 'onBootstrap'), 10000);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'beforeDispatch'), 100);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 0);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 0);
    }

    public function beforeDispatch(MvcEvent $e)
    {
        //TODO: add here for temporary
        //Auto add controller Di alias by global router
        $configuration    = $e->getApplication()->getConfig();
        $routeMatch = $e->getRouteMatch();
        if($routeMatch && $routeMatch  instanceof \Zend\Mvc\Router\RouteMatch){
            $routeMatchName =  $routeMatch->getMatchedRouteName();
            $controllerName =  $routeMatch->getParam('controller');

            if(isset($configuration['router']['routes'][$routeMatchName]) 
                && $routeConfiguration = $configuration['router']['routes'][$routeMatchName]
            ){
                if(isset($routeConfiguration['type']) && $routeConfiguration['type'] === 'Eva\Mvc\Router\Http\ModuleRoute'){
                    $configuration['controller']['classes'][$controllerName] = $controllerName;
                }
            }
        }
        $controllerLoader = $e->getApplication()->getServiceManager()->get('ControllerLoader');
        if (isset($configuration['controller'])) {
            foreach ($configuration['controller'] as $type => $specs) {
                if ($type == 'classes') {
                    foreach ($specs as $name => $value) {
                        $controllerLoader->setInvokableClass($name, $value);
                    }
                }
                if ($type == 'factories') {
                    foreach ($specs as $name => $value) {
                        $controllerLoader->setFactory($name, $value);
                    }
                }
            }
        }
    }

    /**
     * Set module view root path to module/{module name}/view
     * Reset layout by config->module_namespace_layout_map
     * 
     * @return ViewHelperLoader
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeParams = $e->getRouteMatch()->getParams();
        if(false === isset($routeParams['module']) || !$routeParams['module']){
            return false;
        }

        $controller = $e->getTarget();
        if(!$controller instanceof \Zend\Mvc\Controller\AbstractActionController && !$controller instanceof \Zend\Mvc\Controller\AbstractRestfulController){
            //Event should trigger after route matched and found controller
            throw new \Zend\Mvc\Exception\RuntimeException(printf(
                '%s should be only trigger on mvc dispatch event',
                __METHOD__
            ));
        }
        $object = new \ReflectionObject($controller);
        $controllerFullPath = $object->getFileName();
        $controllerClassname = $object->getName();

        $classRootPath = substr($controllerFullPath, 0, 0 - strlen($controllerClassname . '.php'));
        $moduleRootPath = $classRootPath . '..';
        $this->viewRootPath = $moduleRootPath . DIRECTORY_SEPARATOR . 'view';

        $moduleNamespace = isset($routeParams['moduleNamespace']) ? $routeParams['moduleNamespace'] : '';
        if($moduleNamespace && $routeParams['moduleNamespace'] && $routeParams['moduleNamespace'] != $routeParams['module']){
            $this->viewRootPath .= DIRECTORY_SEPARATOR . '_' . strtolower($routeParams['moduleNamespace']);
        }

        if($moduleNamespace && isset($this->config['module_namespace_layout_map']) 
            && !isset($this->config['layout'])
            && isset($this->config['module_namespace_layout_map'][ucfirst($moduleNamespace)])
        ) {
            $viewModel = $this->getViewModel();
            $template = $viewModel->getTemplate();
            if($template == 'layout/layout'){
                $controller->layout('layout/' . $moduleNamespace);
            }
        }

        $templatePathStack = new ViewResolver\TemplatePathStack();
        //All path defined in config will be clear here
        $templatePathStack->setPaths(array($this->viewRootPath));    
        $this->resolver->attach($templatePathStack);
    }

    public function onRender(MvcEvent $event)
    {
        $application  = $event->getApplication();
        $services     = $application->getServiceManager();
        $services->get('Translator');
    }

}
