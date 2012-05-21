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

use Zend\EventManager\EventManagerInterface as Events,
    Zend\EventManager\ListenerAggregateInterface,
    Zend\Filter\Word\CamelCaseToDash as CamelCaseToDashFilter,
    Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch,
    Zend\View\Model\ModelInterface as ViewModel;

class InjectTemplateListener extends \Zend\Mvc\View\InjectTemplateListener
{
    /**
     * Inject a template into the view model, if none present
     *
     * Template is derived from the controller found in the route match, and,
     * optionally, the action, if present.
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function injectTemplate(MvcEvent $e)
    {
        $model = $e->getResult();
        if (!$model instanceof ViewModel) {
            return;
        }

        $template = $model->getTemplate();
        if (!empty($template)) {
            return;
        }

		$routeMatch = $e->getRouteMatch();
		$module = $routeMatch->getParam('module');
		if($module) {
			$controllerName = $routeMatch->getParam('controllerName');
			$action = $routeMatch->getParam('action');
			$model->setTemplate($controllerName . '/' . $action);		
		} else {
			$controller = $e->getTarget();
			if (is_object($controller)) {
				$controller = get_class($controller);
			}
			if (!$controller) {
				$controller = $routeMatch->getParam('controller', '');
			}

			$module     = $this->deriveModuleNamespace($controller);
			$controller = $this->deriveControllerClass($controller);

			$template   = $this->inflectName($module);
			if (!empty($template)) {
				$template .= '/';
			}
			$template  .= $this->inflectName($controller);

			$action     = $routeMatch->getParam('action');
			if (null !== $action) {
				$template .= '/' . $this->inflectName($action);
			}
			$model->setTemplate($template);		
		}
	}
}
