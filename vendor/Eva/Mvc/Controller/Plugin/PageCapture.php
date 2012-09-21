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
use Zend\Cache\PatternFactory;


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
    protected $pageId;

    public function getPageId()
    {
        return $this->pageId;
    }

    public function setPageId($id)
    {
        $this->pageId = $id;
        return $this;
    }

    public function __invoke($pageId = null, $pageExtension = null, array $options = array())
    {
        $this->setPageId($pageId);

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
        
        if (!$pageId) {
            $request = $this->getController()->getEvent()->getRequest();
            $baseUrl = $request->getBaseUrl();
            $path = $request->getUri()->getPath();
            if($baseUrl){
                $path = substr($path, strlen($baseUrl));
            }
        }

        if(isset($config['adapter']) && $config['adapter'] == 'memcached'){
            if (!$pageId) {
                $pageId = $path;
                $this->setPageId($pageId);
            }
            //Note: get global MvcEvent must use getApplication
            $this->getController()->getEvent()->getApplication()->getEventManager()->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
        } else {
            $capture = PatternFactory::factory('capture', $options);

            if($pageId) {
                $pageId .= '.' . $pageExtension;
            } else {
                $pageId = $path . '.' . $pageExtension;
            }

            $capture->start($pageId);
        }

    }

    public function onFinish($event)
    {
        $response = $event->getApplication()->getResponse();
        $pageId = $this->getPageId();
        //Save this to memcached;
        $htmlContent = $response->getContent();
        
        $options = array();
        
        $config = $this->getConfig();
        $config = $config['cache']['page_capture'];
        $options = $config['options'];
        unset($options['public_dir']);
        $cache = new \Zend\Cache\Storage\Adapter\Memcached($options);

        $cache->setItem($pageId, $htmlContent);
    }

    protected function getConfig()
    {
        $controller = $this->getController();
        return $controller->getEvent()->getApplication()->getConfig();
    }

}
