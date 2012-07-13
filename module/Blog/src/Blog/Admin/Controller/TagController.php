<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class TagController extends RestfulModuleController
{
    protected $renders = array(
    );

    public function restIndexTag()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();


        return array(
            'query' => $query,
        );
    }

}
