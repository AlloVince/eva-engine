<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 0);
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

        //$controller = $routeParams['controller'];
        $object = new \ReflectionObject($e->getTarget());
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
            && !isset($this->config['layout']) && isset($this->config['module_namespace_layout_map'][ucfirst($moduleNamespace)])) {
            $this->getViewModel()->setTemplate('layout/' . $moduleNamespace);
        }

        $templatePathStack = new ViewResolver\TemplatePathStack();
        //All path defined in config will be clear here
        $templatePathStack->setPaths(array($this->viewRootPath));    
        $this->resolver->attach($templatePathStack);
    }

    /**
     * Instantiates and configures the renderer's helper loader
     * 
     * @return ViewHelperLoader
     */
     /*
    public function getHelperLoader()
    {
        if ($this->helperLoader) {
            return $this->helperLoader;
        }

        $map = array();
        if (isset($this->config['helper_map'])) {
            $map = $this->config['helper_map'];
        }
        //config will be transform into Zend\Config\Config object
        if (is_array($map) && !in_array('Zend\Form\View\HelperLoader', $map)) {
            array_unshift($map, 'Zend\Form\View\HelperLoader');
        }
        $this->helperLoader = new ViewHelperLoader($map);

        $this->services->setService('ViewHelperLoader', $this->helperLoader);
        $this->services->setAlias('Zend\View\HelperLoader', 'ViewHelperLoader');

        return $this->helperLoader;
    }
    */

    /**
     * Instantiates and configures the default MVC rendering strategy
     * 
     * @return DefaultRenderingStrategy
     */
     /*
    public function getMvcRenderingStrategy()
    {
        if ($this->mvcRenderingStrategy) {
            return $this->mvcRenderingStrategy;
        }

        $this->mvcRenderingStrategy = new \Eva\Mvc\View\DefaultModuleRenderingStrategy($this->getView());
        $this->mvcRenderingStrategy->setLayoutTemplate($this->getLayoutTemplate());

        $this->services->setService('DefaultRenderingStrategy', $this->mvcRenderingStrategy);
        $this->services->setAlias('Eva\Mvc\View\DefaultModuleRenderingStrategy', 'DefaultRenderingStrategy');

        return $this->mvcRenderingStrategy;
    }
    */
}
