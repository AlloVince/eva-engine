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
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Core\Helper;

use Eva\Api;

/**
 * View helper for rendering Form objects
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Evajs extends \Zend\Form\View\Helper\AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     * 
     * @param  ElementInterface $element 
     * @return string
     */
    public function __invoke()
    {
        $routerMatch = Api::_()->getRouterMatch();
        if($routerMatch){
            $params = $routerMatch->getParams();
            $jsParams = array(
                'module' => isset($params['module']) ? $params['module'] : null,
                'moduleNs' => isset($params['moduleNamespace']) ? $params['moduleNamespace'] : null,
                'controller' => isset($params['controllerName']) ? $params['controllerName'] : null,
                'action' => isset($params['action']) ? $params['action'] : null,
            );
        } else {
            $jsParams = array();
        }

        $lang = 'en';
        $sm = Api::_()->getServiceManager();
        if($sm->has('translator')){
            $lang = $sm->get('translator')->getLocale();
        }
        
        $config = Api::_()->getConfig();
        $jsConfig = array_merge(array(
            'debug' => false,
            'version' => 1,
            'lang' => 'en',
            'dir' => $this->view->uri('/', '-b'),
            'f' => $this->view->uri($this->view->serverUrl() . '/', '-b'),
            's' => $this->view->link('/', '-B'),
            'lang' => $lang,
            'ie' => false,
        ), $jsParams);
        return '<script type="text/javascript">var eva_config = ' . \Zend\Json\Json::encode($jsConfig) . '</script>';
    }
}
