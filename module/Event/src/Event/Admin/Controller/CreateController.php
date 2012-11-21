<?php
namespace Event\Admin\Controller;

use Event\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CreateController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexCreate' => 'event/get',    
    );

    public function restIndexCreate()
    {
        return array(
        );
    }
}
