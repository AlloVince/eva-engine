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

use Zend\Mvc\Router\RouteStackInterface,
    Zend\Mvc\Router\RouteMatch,
    Zend\View\Exception,
    Eva\Uri\Uri as CoreUri;

/**
 * Render View Partial Cross Module
 * 
 * @category   Eva
 * @package    Eva_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Widget extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Variable to which object will be assigned
     * @var string
     */
    protected $_objectKey;

    /**
     * Renders a template fragment within a variable scope distinct from the
     * calling View object.
     *
     * If no arguments are passed, returns the helper instance.
     *
     * If the $model is an array, it is passed to the view object's assign()
     * method.
     *
     * If the $model is an object, it first checks to see if the object
     * implements a 'toArray' method; if so, it passes the result of that
     * method to to the view object's assign() method. Otherwise, the result of
     * get_object_vars() is passed.
     *
     * @param  string $name Name of view script
     * @param  array $model Variables to populate in the view
     * @return string|Partial
     * @throws Exception\RuntimeException
     */
    public function __invoke($moduleName = null, $name = null, $model = null)
    {
        if (0 == func_num_args()) {
            return $this;
        }
        
        //$model = new \Zend\View\Model\ViewModel();
        //$model->setTemplate('D:\xampp\htdocs\zf2\module\Core\view\widgets\paginator.phtml');

        $view = $this->cloneView();
        if (isset($this->partialCounter)) {
            $view->partialCounter = $this->partialCounter;
        }

        $modulePath = EVA_MODULE_PATH . DIRECTORY_SEPARATOR . ucfirst($moduleName) . DIRECTORY_SEPARATOR . 'view';
        $resolver = new \Zend\View\Resolver\TemplatePathStack();
        $resolver->addPaths(array($modulePath));
        $view->setResolver($resolver);

        if (!empty($model)) {
            if (is_array($model)) {
                $view->vars()->assign($model);
            } elseif (is_object($model)) {
                if (null !== ($objectKey = $this->getObjectKey())) {
                    $view->vars()->offsetSet($objectKey, $model);
                } elseif (method_exists($model, 'toArray')) {
                    $view->vars()->assign($model->toArray());
                } else {
                    $view->vars()->assign(get_object_vars($model));
                }
            }
        }
        return $view->render($name);
    }

    /**
     * Clone the current View
     *
     * @return \Zend\View\Renderer\RendererInterface
     */
    public function cloneView()
    {
        $view = clone $this->view;
        $view->setVars(array());
        return $view;
    }

    /**
     * Set object key
     *
     * @param  string $key
     * @return Partial
     */
    public function setObjectKey($key)
    {
        if (null === $key) {
            $this->_objectKey = null;
        } else {
            $this->_objectKey = (string) $key;
        }

        return $this;
    }

    /**
     * Retrieve object key
     *
     * The objectKey is the variable to which an object in the iterator will be
     * assigned.
     *
     * @return null|string
     */
    public function getObjectKey()
    {
        return $this->_objectKey;
    }
}
