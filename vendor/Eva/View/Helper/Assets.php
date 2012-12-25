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

namespace Eva\View\Helper;

use Zend\View\Exception;

/**
* View helper for js assets
* 
* @category   Zend
* @package    Zend_Form
* @subpackage View
* @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
* @license    http://framework.zend.com/license/new-bsd     New BSD License
*/
class Assets extends \Zend\View\Helper\AbstractHelper
{
    /**
    * Invoke helper as functor
    *
    * Proxies to {@link render()}.
    * 
    * @param  ElementInterface $element 
    * @return string
    */
    public function __invoke($file = array(), $compress = false)
    {
        if(!$file){
            return $this;
        }

        $view = $this->getView();
        $config = $view->getHelperPluginManager()->getServiceLocator()->get('config');
        $prefix = $config['site']['assets']['basePath'];

        if (is_string($file)){
            return $prefix . $file;
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Assets helper require file path by input'
            ));
        }
        return $this;
    }
}
