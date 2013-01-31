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
    public function __construct()
    {
        $this->defaultServiceConfig['factories']['ControllerPluginManager'] = 'Eva\Mvc\Service\ControllerPluginManagerFactory';
        $this->defaultServiceConfig['factories']['ViewHelperManager'] = 'Eva\Mvc\Service\ViewHelperManagerFactory';
        $this->defaultServiceConfig['factories']['ViewManager'] = 'Eva\Mvc\Service\ViewManagerFactory';
    
        $this->defaultServiceConfig['aliases']['Eva\Mvc\Controller\PluginManager'] = 'ControllerPluginManager';
    }

}
