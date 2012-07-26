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
        \File\Model\FileTransferFactory::factory();
    }
}
