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
    Eva\Api,
    Eva\Uri\Uri as CoreUri;

/**
 * Helper for making easy links and getting urls that depend on the routes and router.
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class AbstractUri extends \Zend\View\Helper\AbstractHelper
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
        'B' => 'setBasePath',
        'b' => 'setExtraPath',
        'c' => 'setCallback',
        'C' => 'setCallbackName',
        'd' => 'deleteEmptyQuery',
        'e' => 'htmlEncode',
        'E' => 'urlEncode',
        'f' => 'fragment',
        'h' => 'setHost',
        'p' => 'setPost',
        'Q' => 'setBaseQuery',
        'q' => 'setExtraQuery',
        's' => 'setScheme',
        'v' => 'setVersion',
        'V' => 'setVersionName',
    );

    
    protected $defaultUriOptions;

    protected $defaultConfigKey = 'uri';

    /*
     * Default resource url rule:
     * Enable global query
     * Enable global basePath
     * Html encoded
     * Enable config host
    */
    protected $defaultArgs;
    
    //protected $defaultArgs = 'Qeh';

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

    public function getDefaultArgs()
    {
        return $this->defaultArgs;
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
            'setBaseQuery' => $this->view->baseQuery,
            'setVersion' => '',
            'setVersionName' => 'v',
        );

        $config = Api::_()->getConfig();
        if(isset($config['site'][$this->defaultConfigKey]) && is_array($config['site'][$this->defaultConfigKey])){
            $urlOptions = array();
            $config = $config['site'][$this->defaultConfigKey];
            foreach($config as $key => $value){
                $key = 'set' . ucfirst($key);
                $urlOptions[$key] = $value;
            }
            $defaultUriOptions = array_merge($defaultUriOptions, $urlOptions);
        }

        return $this->defaultUriOptions = $defaultUriOptions;
    }

    /**
     * $this-uri('/blog/1', 'q', array('q' => array('page' => 1)))
     * $this-uri('/blog/1', array('page' => 1)) === $this-uri('/blog/1', 'q', array('q' => array('page' => 1)))
     * $this-uri('admin/blog/1','qf') === $this-uri('admin/blog/1','-BEeqf')
     * 
     */
    public function __invoke($resourceString = '', $arg = '', array $options = array())
    {
        $uri = new CoreUri($resourceString);


        //short cut for just setting query
        if(true === is_array($arg)){
            $options = array_merge($options, array(
                'q' => $arg
            ));
            $arg = 'q';
        }

        $defaultArgs = str_split($this->getDefaultArgs());
        $argArray = array();
        if($arg && is_string($arg)){
            $argArray = str_split($arg);
        }

        //Arg start with - will rewrite default options
        if($argArray && $argArray[0] == '-'){
            $argArray = array_unique($argArray);
        } else {
            $argArray = array_unique(array_merge($defaultArgs, $argArray));
        }

        $argMap = $this->argMap;
        $defaultUriOptions = $this->getDefaultUriOptions();
        $localOptions = array();
        foreach($options as $key => $value){
            if(isset($argMap[$key])) {
                $localOptions[$argMap[$key]] = $value;
                unset($options[$key]);
            }
        }
        $options = array_merge($defaultUriOptions, $localOptions);

        //p($argArray);
        //p($options);
        foreach($argArray as $functionShortName){
            if(!isset($argMap[$functionShortName])){
                continue;
            }

            $function = $argMap[$functionShortName];
            $functionParam = isset($options[$function]) ? $options[$function] : null;
            
            //p($functionShortName . '=>'. $function . '=>');
            //p($functionParam);

            $uri->$function($functionParam);
        }
        //p($uri);
        //p($uri->toString());
        return $uri->toString();
    }
}
