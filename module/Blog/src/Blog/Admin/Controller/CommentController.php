<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CommentController extends RestfulModuleController
{
    protected $renders = array(
    );

    public function restIndexComment()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();


        return array(
            'query' => $query,
        );
    }

}
