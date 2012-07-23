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
 * @subpackage Controller\Plugin
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;


/**
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Controller\Plugin
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class PageCapture extends AbstractPlugin
{
    public function __invoke($pageId, $pageExtension = null, array $options = array())
    {
        $config = $this->getConfig();
        if(!isset($config['cache']['page_capture'])){
            return false;
        }

        $config = $config['cache']['page_capture'];
        if(!isset($config['enable']) || !$config['enable']){
            return false;
        }

        if(!$pageExtension){
            $pageExtension = isset($config['page_extension']) && $config['page_extension'] ? $config['page_extension'] : 'html';
        }

        if(!$options){
            $options = isset($config['options']) && $config['options'] ? $config['options'] : 
                array(
                    'public_dir' => EVA_PUBLIC_PATH . '/static/cache/',
                );
        }

        $capture = \Zend\Cache\PatternFactory::factory('capture', $options);

        $pageId .= '.' . $pageExtension;
        $capture->start($pageId);
    }


    protected function getConfig()
    {
        $controller = $this->getController();
        return $controller->getEvent()->getApplication()->getConfig();
    }

}
