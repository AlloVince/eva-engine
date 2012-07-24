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

namespace Eva\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;


/**
* A short cut of using Zend\Cache\PatternFactory in controller
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Controller\Plugin
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
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
