<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class AddController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexAdd' => 'blog/get',    
    );

    public function restIndexAdd()
    {
        $request = $this->getRequest();
        return array(
        );
    }
}
