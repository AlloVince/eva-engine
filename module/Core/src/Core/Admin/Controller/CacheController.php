<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CacheController extends RestfulModuleController
{
    protected $addResources = array(
        'static'
    );

    public function restIndexCache()
    {
    }

    public function restGetCacheStatic()
    {
        $config = $this->getEvent()->getApplication()->getConfig();
        if(!isset($config['cache']['page_capture'])){
            $this->flashMessenger()->addMessage('no-cache-found');
            return $this->redirect()->toUrl('/admin/cache/');
        }
        $cachePath = $config['cache']['page_capture']['options']['public_dir'];
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}
