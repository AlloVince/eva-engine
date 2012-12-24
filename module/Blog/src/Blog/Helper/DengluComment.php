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

namespace Blog\Helper;

use Zend\View\Exception;


/**
 * View helper for rendering Form objects
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class DengluComment extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     * 
     * @param  ElementInterface $element 
     * @return string
     */
    public function __invoke($postIdOrUrl = null, $options = array())
    {
        $config = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('config');
        $defaultConfig = array(
            'websiteId' => '',
        );
        if(isset($config['blog']['comment_social']['denglu'])){
            $defaultConfig = array_merge($defaultConfig, $config['blog']['comment_social']['denglu']);
        }
        $options = array_merge($defaultConfig, $options);
        if(!$options['websiteId']){
            throw new Exception\InvalidArgumentException(sprintf(
                'No website ID found in youyan comment helper'
            ));
        }

        return "<script type='text/javascript' charset='utf-8' src='http://open.denglu.cc/connect/commentcode?appid={$options['websiteId']}'></script>";
    }
}
