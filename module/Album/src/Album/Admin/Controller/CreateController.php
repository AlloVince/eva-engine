<?php
namespace Album\Admin\Controller;

use Album\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CreateController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexCreate' => 'album/get',    
    );

    public function restIndexCreate()
    {
        return array(
        );
    }
}
