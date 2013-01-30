<?php
namespace Notification\Admin\Controller;

use Notification\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CreateController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexCreate' => 'notification/get',    
    );

    public function restIndexCreate()
    {
        return array(
        );
    }
}
