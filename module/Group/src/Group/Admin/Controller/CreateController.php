<?php
namespace Group\Admin\Controller;

use Group\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CreateController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexCreate' => 'group/get',    
    );

    public function restIndexCreate()
    {
        return array(
        );
    }
}
