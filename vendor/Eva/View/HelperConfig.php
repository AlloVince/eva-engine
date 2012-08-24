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

namespace Eva\View;

use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Service manager configuration for form view helpers
 *
 * @category   Eva
 * @package    Eva_View
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HelperConfig implements ConfigInterface
{
    /**
     * @var array Pre-aliased view helpers
     */
     protected $invokables = array(
         'action' => 'Eva\View\Helper\Action',
         'uri' => 'Eva\View\Helper\Uri',    
         'link' => 'Eva\View\Helper\Link',
         'formAttr' => 'Eva\Form\View\Helper\FormAttr',
         'input' => 'Eva\Form\View\Helper\Input',
         'label' => 'Eva\Form\View\Helper\Label',
         'widget' => 'Eva\View\Helper\Widget',
         'textDelay' => 'Eva\View\Helper\TextDelay',
         'hasModule' => 'Eva\View\Helper\HasModule',
         'thumb' => 'Eva\View\Helper\Thumb',
         'datetime' => 'Eva\View\Helper\Datetime',
         'googleAnalytics' => 'Eva\View\Helper\GoogleAnalytics',
     );

    /**
     * Configure the provided service manager instance with the configuration
     * in this class.
     *
     * In addition to using each of the internal properties to configure the
     * service manager, also adds an initializer to inject ServiceManagerAware
     * classes with the service manager.
     *
     * @param  ServiceManager $serviceManager
     * @return void
     */
    public function configureServiceManager(ServiceManager $serviceManager)
    {
        foreach ($this->invokables as $name => $service) {
            $serviceManager->setInvokableClass($name, $service);
        }
    }
}
