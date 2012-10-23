<?php
namespace Video\Admin\Controller;

use Video\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CreateController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexCreate' => 'activity/get',    
    );

    public function restIndexCreate()
    {
        return array(
        );
    }
}
