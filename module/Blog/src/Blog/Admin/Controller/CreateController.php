<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CreateController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexCreate' => 'blog/get',    
    );

    public function restIndexCreate()
    {
        return array(
        );
    }
}
