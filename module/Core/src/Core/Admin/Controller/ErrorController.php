<?php

namespace Core\Admin\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class ErrorController extends ActionController
{
    const ERROR_UNAUTHORIZED = 401;
    const ERROR_NO_ROUTE = 404;
    const ERROR_NO_CONTROLLER = 404;

    public function indexAction()
    {
        $this->layout('layout/adminindex');
        $error = array(
            'type' => 401,
            'message' => 'Unauthorized admin resource',
        );
        switch ($error['type']) {
            case self::ERROR_NO_ROUTE:
            case self::ERROR_NO_CONTROLLER:
            default:
                // 404 error -- controller or action not found
                $this->response->setStatusCode(401);
                break;
        }

        return $this->redirect()->toUrl('/admin/');
        //return new ViewModel(array('message' => $error['message']));
    }
}
