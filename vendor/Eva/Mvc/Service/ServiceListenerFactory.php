<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Mvc
 * @author    AlloVince
 */

namespace Eva\Mvc\Service;

/**
 * @category   Eva
 * @package    Eva_Mvc
 */
class ServiceListenerFactory extends \Zend\Mvc\Service\ServiceListenerFactory
{
    protected $defaultServiceConfig = array(
        'invokables' => array(
            'DispatchListener' => 'Zend\Mvc\DispatchListener',
            'RouteListener'    => 'Zend\Mvc\RouteListener',
        ),
        'factories' => array(
            'Application'             => 'Zend\Mvc\Service\ApplicationFactory',
            'Config'                  => 'Zend\Mvc\Service\ConfigFactory',
            'ControllerLoader'        => 'Zend\Mvc\Service\ControllerLoaderFactory',
            //'ControllerPluginManager' => 'Zend\Mvc\Service\ControllerPluginManagerFactory',
            'ControllerPluginManager' => 'Eva\Mvc\Service\ControllerPluginManagerFactory',
            'ConsoleAdapter'          => 'Zend\Mvc\Service\ConsoleAdapterFactory',
            'ConsoleRouter'           => 'Zend\Mvc\Service\RouterFactory',
            'DependencyInjector'      => 'Zend\Mvc\Service\DiFactory',
            'HttpRouter'              => 'Zend\Mvc\Service\RouterFactory',
            'Request'                 => 'Zend\Mvc\Service\RequestFactory',
            'Response'                => 'Zend\Mvc\Service\ResponseFactory',
            'Router'                  => 'Zend\Mvc\Service\RouterFactory',
            //'ViewHelperManager'       => 'Zend\Mvc\Service\ViewHelperManagerFactory',
            'ViewHelperManager'       => 'Eva\Mvc\Service\ViewHelperManagerFactory',
            'ViewFeedRenderer'        => 'Zend\Mvc\Service\ViewFeedRendererFactory',
            'ViewFeedStrategy'        => 'Zend\Mvc\Service\ViewFeedStrategyFactory',
            'ViewJsonRenderer'        => 'Zend\Mvc\Service\ViewJsonRendererFactory',
            'ViewJsonStrategy'        => 'Zend\Mvc\Service\ViewJsonStrategyFactory',
            //'ViewManager'             => 'Zend\Mvc\Service\ViewManagerFactory',
            'ViewManager'             => 'Eva\Mvc\Service\ViewManagerFactory',
            'ViewResolver'            => 'Zend\Mvc\Service\ViewResolverFactory',
            'ViewTemplateMapResolver' => 'Zend\Mvc\Service\ViewTemplateMapResolverFactory',
            'ViewTemplatePathStack'   => 'Zend\Mvc\Service\ViewTemplatePathStackFactory',
        ),
        'aliases' => array(
            'Configuration'                          => 'Config',
            'Console'                                => 'ConsoleAdapter',
            'ControllerPluginBroker'                 => 'ControllerPluginManager',
            'Di'                                     => 'DependencyInjector',
            'Zend\Di\LocatorInterface'               => 'DependencyInjector',
            'Zend\Mvc\Controller\PluginBroker'       => 'ControllerPluginBroker',
            //'Zend\Mvc\Controller\PluginManager'      => 'ControllerPluginManager',
            'Eva\Mvc\Controller\PluginManager'      => 'ControllerPluginManager',
            'Zend\View\Resolver\TemplateMapResolver' => 'ViewTemplateMapResolver',
            'Zend\View\Resolver\TemplatePathStack'   => 'ViewTemplatePathStack',
            'Zend\View\Resolver\AggregateResolver'   => 'ViewResolver',
            'Zend\View\Resolver\ResolverInterface'   => 'ViewResolver',
        ),
    );

}
