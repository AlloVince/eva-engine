<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;
use GlobIterator;
use Zend\Stdlib\ErrorHandler;

class CacheController extends RestfulModuleController
{
    protected $addResources = array(
        'static',
        'memcached',
    );

    public function restIndexCache()
    {
        $config = $this->getEvent()->getApplication()->getConfig();
        
        if($config['cache']['page_capture']['adapter'] == 'memcached'){
            $config = $config['cache']['page_capture'];

            $stats = $this->getStats($config);

            if ($stats) {
                foreach ($stats as $key=>$server) {
                    $stats[$key]['totalSpace'] = $server['limit_maxbytes'];
                    $stats[$key]['availableSpace'] = $server['limit_maxbytes'] - $server['bytes'];
                    $stats[$key]['hitRate'] = round($server['get_hits']/$server['cmd_get'] * 100, 2);
                }
            }

            return array(
                'stats' => $stats,
            );
        }    
    }
    
    public function getStats($config)
    {
        if (!$config) {
            return array();
        }
    
        $memcached = new \Memcached();
        $memcached->setOption($memcached::OPT_PREFIX_KEY, $config['options']['namespace']);
        $memcached->addServers($config['options']['servers']);
        $stats = $memcached->getStats();
        
        return $stats;
    }

    /**
     * Remove files
     *
     * @param string $namespace
     * @return boolean
     */
    protected function clear($rootPath, $ext = 'html', $level = 4)
    {
        $flags = GlobIterator::SKIP_DOTS | GlobIterator::CURRENT_AS_PATHNAME;

        $files = array();
        ErrorHandler::start();
        for($i = 0; $i < $level; $i++){
            $path = $rootPath . str_repeat(\DIRECTORY_SEPARATOR . '*', $i)
                . \DIRECTORY_SEPARATOR . '*' . '.' . $ext;
            $glob = new GlobIterator($path, $flags);
            foreach ($glob as $pathname) {
                $files[] = $pathname;
                unlink($pathname);
            }
        }
        $error = ErrorHandler::stop();
        if ($error) {
            throw new \Exception("Failed to remove file '{$pathname}'", 0, $error);
        }

        return $files;
    }

    /**
     * Flush cache
     *
     * @param string $namespace
     * @return boolean
     */
    protected function flush($config)
    {
        $options = $config['options'];
        unset($options['public_dir']);
        $cache = new \Zend\Cache\Storage\Adapter\Memcached($options); 
        $totalSpace = $cache->getTotalSpace();
        $availableSpace = $cache->getAvailableSpace(); 
    
        $cache->flush();
    
        return ($totalSpace - $availableSpace);
    }

    public function restGetCacheStatic()
    {
        $config = $this->getEvent()->getApplication()->getConfig();
        if(!isset($config['cache']['page_capture'])){
            $this->flashMessenger()->addMessage('no-cache-found');
            return $this->redirect()->toUrl('/admin/core/cache/');
        }
        $cachePath = $config['cache']['page_capture']['options']['public_dir'];
        $cacheExt = $config['cache']['page_capture']['page_extension'];
        $files = $this->clear($cachePath, $cacheExt);

        return array('files' => $files);
    }

    public function restGetCacheMemcached()
    {
        $config = $this->getEvent()->getApplication()->getConfig();

        if(!isset($config['cache']['page_capture'])){
            $this->flashMessenger()->addMessage('no-cache-found');
            return $this->redirect()->toUrl('/admin/core/cache/');
        }

        $config = $config['cache']['page_capture'];
        $releaseSpace = $this->flush($config);

        return array('releaseSpace' => $releaseSpace);
    }
}
