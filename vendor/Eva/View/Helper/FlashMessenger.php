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

namespace Eva\View\Helper;

use Zend\View\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Call FlashMessenger plugin in view helper
 * 
 * @category   Eva
 * @package    Eva_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class FlashMessenger extends \Zend\View\Helper\AbstractHelper implements ServiceLocatorAwareInterface
{

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function __invoke($messageId = null)
    {
        $flashMessenger = $this->getServiceLocator()->getServiceLocator()->get('controllerPluginManager')->get('FlashMessenger');
        if(!$messageId) {
            return $flashMessenger;
        }

        if(!$flashMessenger->hasMessages()){
            return false;
        }

        $messages = $flashMessenger->getMessages();
        if(in_array($messageId, $messages)){
            $flashMessenger->clearMessages();
            foreach($messages as $message){
                if($message == $messageId){
                    continue;
                }
                $flashMessenger->addMessage($message);
            }
            return true;
        }

        return false;
    }

}
