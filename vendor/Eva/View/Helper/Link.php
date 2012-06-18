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

use Eva\Uri\Uri,
    Zend\View\Exception;

/**
 * Helper for making easy links and getting urls that depend on the routes and router.
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Link extends \Zend\View\Helper\AbstractHelper
{
    protected $options = array(
        'basePath' => '',
        'baseQuery' => array(),
        'scheme' => 'http',
        'host' => '',
        'path' => '',
        'query' => array(),
        'version' => '',
    );

    protected 

    protected $optionsInited = false;

    public function getOptions()
    {
        return $this->options;
    }

    public function mergeOptions(array $newOptions = array())
    {
    
    }

    public function initOptions()
    {
        $basePath = $this->view->basePath();
    
    }

    public function resetOptions()
    {
    
    }

    /**
     * Generates an url given the name of a route.
     *
     * @see    Zend\Mvc\Router\RouteInterface::assemble()
     * @param  string  $name               Name of the route
     * @param  array   $params             Parameters for the link
     * @param  array   $options            Options for the route
     * @param  boolean $reuseMatchedParams Whether to reuse matched parameters
     * @return string Url                  For the link href attribute
     * @throws Exception\RuntimeException  If no RouteStackInterface was provided
     * @throws Exception\RuntimeException  If no RouteMatch was provided
     * @throws Exception\RuntimeException  If RouteMatch didn't contain a matched route name
     */
    public function __invoke($resourceString = '', $arg = '', array $options = array())
    {
        $options = $options ? $this->mergeOptions($options) : $this->options;
        $uri = new Uri($resourceString);

        return $resourceString;
    }
}
