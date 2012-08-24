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
        'static'
    );

    public function restIndexCache()
    {
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
}
