<?php
namespace Crontab\Admin\Controller;

use Crontab\Service\Crontab,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CrontabController extends RestfulModuleController
{
    protected $renders = array(
        'restPostCrontab' => 'crontab/index',    
    );

    public function restIndexCrontab()
    {
        $crontab = new Crontab();
        $content = $crontab->getCrontabList();

        return array(
            'content' => $content,
        );
    }


    public function restPostCrontab()
    {
        $postData = $this->params()->fromPost();
        
        $crontab = new Crontab();
        
        if ($postData['content']) {
            $crontab->saveCrontabList($postData['content']);
        } else {
            $crontab->cleanCrontab();
        }    
        
        $this->redirect()->toUrl('/admin/crontab/');

        return array(
            'content' => $postData['content'],
        );
    }
}
