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

use Zend\View\Helper\AbstractHelper,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\View\Exception;

/**
* Render View Partial Cross Module
* 
* @category   Eva
* @package    Eva_View
* @subpackage Helper
* @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
* @license    http://framework.zend.com/license/new-bsd     New BSD License
*/
class Widget extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Variable to which object will be assigned
     * @var string
     */
    protected $objectKey;


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

        $view = $this->cloneView();
        if (isset($this->partialCounter)) {
            $view->partialCounter = $this->partialCounter;
        }

        $module = $this->serviceLocator->getServiceLocator()->get('modulemanager')->getModule($moduleName);
        $object = new \ReflectionObject($module);
        $modulePath = dirname($object->getFileName());

        $modulePath .= DIRECTORY_SEPARATOR . 'view';
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
            $this->objectKey = null;
        } else {
            $this->objectKey = (string) $key;
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
        return $this->objectKey;
    }

}
