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

namespace Eva\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;
use Zend\Cache\PatternFactory;


/**
* A short cut of using ViewHelper in controller
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Controller\Plugin
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ViewHelper extends AbstractPlugin
{
    public function __invoke($helperName = null)
    {
        $helperManager = $this->getController()->getEvent()->getApplication()->getServiceManager()->get('viewhelpermanager');
        if(!$helperName){
            return $helperManager;
        }
        $helper = $helperManager->get($helperName);

        $args = func_get_args();
        //remove helper name
        array_shift($args);

        if($args){
            return call_user_func_array($helper, $args);
        } else {
            return $helper();
        }
    }

}
