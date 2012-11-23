<?php
namespace User\Admin\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CreateController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexCreate' => 'user/get',    
    );

    public function restIndexCreate()
    {
        $request = $this->getRequest();

        return array(
        );
    }
}
