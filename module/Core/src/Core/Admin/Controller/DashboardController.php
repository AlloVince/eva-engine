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
            $postModel = Api::_()->getModelService('Blog\Model\Post');
            $posts = $postModel->getPostList();
            $postsCount = $postModel->getItem()->getDataClass()->find('count');
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


        if($api->isModuleLoaded('User')){
            $userModel = Api::_()->getModelService('User\Model\User');
            $users = $userModel->setItemList(array('page' => 1))->getUserList();
            if($userModel->getPaginator()) {
                $usersCount = $userModel->getPaginator()->getRowCount();
            } else {
                $usersCount = 0;
            }
            $view['users'] = $users;
            $view['usersCount'] = $usersCount;
        }

        return new ViewModel($view); 
    }
}
