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
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\View;

use Zend\ServiceManager\ConfigurationInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Service manager configuration for form view helpers
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HelperConfiguration implements ConfigurationInterface
{
    /**
     * @var array Pre-aliased view helpers
     */
     protected $invokables = array(
         'uri' => 'Eva\View\Helper\Uri',    
         'link' => 'Eva\View\Helper\Link',
         'formAttr' => 'Eva\Form\View\Helper\FormAttr',
         'input' => 'Eva\Form\View\Helper\Input',
         'label' => 'Eva\Form\View\Helper\Label',
         'widget' => 'Eva\View\Helper\Widget',
         'textDelay' => 'Eva\View\Helper\TextDelay',
         '_' => 'Eva\I18n\View\Helper\Translator',
         'evajs' => 'Core\Helper\Evajs',
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
