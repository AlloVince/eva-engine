<?php
namespace File\Admin\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class UploadController extends RestfulModuleController
{
    public function restIndexUpload()
    {
        $fileTransfer = \Eva\File\Transfer\TransferFactory::factory();
        //p($fileTransfer);

        /*
        $postModel = Api::_()->getModel('Blog\Model\Post');
        $cache = $postModel->cache();
        for($i = 0; $i < 100; $i++){
            $cache->setItem(\Eva\Stdlib\String\Hash::uniqueHash(), 1);
        }
        */
    }
}
