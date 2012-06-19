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
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\View\Helper;

use Zend\Mvc\Router\RouteStackInterface,
    Zend\Mvc\Router\RouteMatch,
    Zend\View\Exception,
    Eva\Uri\Uri as CoreUri;

/**
 * Helper for making easy links and getting urls that depend on the routes and router.
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Uri extends \Zend\View\Helper\AbstractHelper
{
    /**
     * RouteStackInterface instance.
     * 
     * @var RouteStackInterface
     */
    protected $router;
    
    /**
     * RouteInterface match returned by the router.
     * 
     * @var RouteMatch.
     */
    protected $routeMatch;

    protected $uri;

    protected $argMap = array(
        'b' => 'addBasePath',
        'B' => 'setBasePath',
        'c' => 'setCallback',
        'C' => 'setCallbackName',
        'e' => 'toHtmlEncodeString',
        'E' => 'toUrlEncodeString',
        'h' => 'setHost',
        'p' => 'setPost',
        'Q' => 'setBaseQuery',
        'q' => 'addBaseQuery',
        's' => 'setScheme',
        'v' => 'addVersion',
        'V' => 'setVersionName',
    );

    
    protected $defaultUriOptions;

    /**
     * Set the router to use for assembling.
     * 
     * @param RouteStackInterface $router
     * @return Url
     */
    public function setRouter(RouteStackInterface $router)
    {
        $this->router = $router;
        return $this;
    }
    
    /**
     * Set route match returned by the router.
     * 
     * @param  RouteMatch $routeMatch
     * @return self
     */
    public function setRouteMatch(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }

    public function getDefaultUriOptions()
    {
        if($this->defaultUriOptions){
            return $this->defaultUriOptions;
        }

        $defaultUriOptions = array(
            'setBasePath' => $this->view->basePath(),
            'setCallbackName' => 'callback',
            'setHost' => '',
            'setBaseQuery' => $this->view->_baseQuery,
            'addVersion' => '',
            'setVersionName' => 'v',
        );

        return $this->defaultUriOptions = $defaultUriOptions;
    }

    /**
     * $this-uri('/blog/1', 'q', array('q' => array('page' => 1)))
     * $this-uri('/blog/1', array('page' => 1)) === $this-uri('/blog/1', 'q', array('q' => array('page' => 1)))
     * $this-uri('admin/blog/1','qf') === $this-uri('admin/blog/1','-BEeqf')
     * 
     */
    public function __invoke($resourceString = '', $arg = 'BEe', array $options = array())
    {
        $uri = new CoreUri($resourceString);

        $defaultUriOptions = $this->getDefaultUriOptions();

        //short cut for just setting query
        if(true === is_array($arg)){
            $options = array_merge($options, array(
                'q' => $arg
            ));
        }

        $argMap = $this->argMap;
        $localOptions = array();
        foreach($options as $key => $value){
            $localOptions[$argMap[$key]] = $value;
        }
        $options = array_merge($defaultUriOptions, $localOptions);

        foreach($options as $key => $value){
            if(!$value){
                continue;
            }
            $uri->$key($value);
        }
        //$args = str_split($arg);
        return $uri->toString();
    }
}
