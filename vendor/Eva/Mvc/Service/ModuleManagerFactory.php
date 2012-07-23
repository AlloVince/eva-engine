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
 * @subpackage Service
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\Mvc\Service;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Service
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ModuleManagerFactory extends \Zend\Mvc\Service\ModuleManagerFactory
{
    /**
     * Default mvc-related service configuration -- can be overridden by modules.
     *
     * @var array
     */
    protected $defaultServiceConfig = array(
        'invokables' => array(
            'DispatchListener' => 'Zend\Mvc\DispatchListener',
            'Request'          => 'Zend\Http\PhpEnvironment\Request',
            'Response'         => 'Zend\Http\PhpEnvironment\Response',
            'RouteListener'    => 'Zend\Mvc\RouteListener',
            'ViewManager'      => 'Eva\Mvc\View\ModuleViewManager',
        ),
        'factories' => array(
            'Application'             => 'Zend\Mvc\Service\ApplicationFactory',
            'Config'                  => 'Zend\Mvc\Service\ConfigFactory',
            'ControllerLoader'        => 'Zend\Mvc\Service\ControllerLoaderFactory',
            'ControllerPluginManager' => 'Zend\Mvc\Service\ControllerPluginManagerFactory',
            'DependencyInjector'      => 'Zend\Mvc\Service\DiFactory',
            'Router'                  => 'Zend\Mvc\Service\RouterFactory',
            'ViewHelperManager'       => 'Eva\Mvc\Service\ViewHelperManagerFactory',
            'ViewFeedRenderer'        => 'Zend\Mvc\Service\ViewFeedRendererFactory',
            'ViewFeedStrategy'        => 'Zend\Mvc\Service\ViewFeedStrategyFactory',
            'ViewJsonRenderer'        => 'Zend\Mvc\Service\ViewJsonRendererFactory',
            'ViewJsonStrategy'        => 'Zend\Mvc\Service\ViewJsonStrategyFactory',
            'Translator'              => 'Eva\I18n\Translator\TranslatorServiceFactory',
        ),
        'aliases' => array(
            'Configuration'                     => 'Config',
            'ControllerPluginBroker'            => 'ControllerPluginManager',
            'Di'                                => 'DependencyInjector',
            'Zend\Di\LocatorInterface'          => 'DependencyInjector',
            'Zend\Mvc\Controller\PluginBroker'  => 'ControllerPluginBroker',
            'Zend\Mvc\Controller\PluginManager' => 'ControllerPluginManager',
        ),
    );

}
