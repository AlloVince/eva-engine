<?php
namespace File\Api\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class UploadController extends RestfulModuleController
{
    public function restIndexUpload()
    {
        $this->layout('layout/adminblank');
    }
}
