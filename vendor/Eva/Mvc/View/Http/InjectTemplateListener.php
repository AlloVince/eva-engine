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


namespace Eva\Mvc\View\Http;

use Zend\Mvc\MvcEvent,
    Zend\View\Model\ModelInterface as ViewModel;

/**
 * Change default render template name
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage View
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class InjectTemplateListener extends \Zend\Mvc\View\Http\InjectTemplateListener
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
