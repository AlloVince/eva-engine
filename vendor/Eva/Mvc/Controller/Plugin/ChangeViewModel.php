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
* A short cut of using Zend\Cache\PatternFactory in controller
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Controller\Plugin
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ChangeViewModel extends AbstractPlugin
{
    public function __invoke($viewModelType)
    {
        $viewModelType = ucfirst(strtolower($viewModelType));
        $strategyName = 'View' . $viewModelType . 'Strategy';
        $view         = $this->getController()->getServiceLocator()->get('Zend\View\View');
        $view->getEventManager()->attach($this->getController()->getServiceLocator()->get('ViewJsonStrategy'), 100);
    }

}
