<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class DashboardController extends ActionController
{
    public function indexAction()
    {
        $api = Api::_();
        $view = array();
        if($api->isModuleLoaded('Blog')){
            $postModel = Api::_()->getModel('Blog\Model\Post');
            $posts = $postModel->getPosts();
            $postsCount = $postModel->getItemTable()->find('count');
            $view['posts'] = $posts;
            $view['postsCount'] = $postsCount;
        }

        if($api->isModuleLoaded('File')){
            $fileModel = Api::_()->getModel('File\Model\File');
            $files = $fileModel->getFiles();
            $filesCount = $fileModel->getItemTable()->find('count');
            $view['files'] = $files;
            $view['filesCount'] = $filesCount;
        }

        return new ViewModel($view); 
    }
}
