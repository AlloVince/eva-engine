<?php

namespace Core\Admin\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class ErrorController extends ActionController
{
    const ERROR_UNAUTHORIZED = 401;
    const ERROR_NO_ROUTE = 404;
    const ERROR_NO_CONTROLLER = 404;

    protected function getCurrentUrl()
    {
        $pageURL = 'http';

        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80"){
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

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

        return $this->redirect()->toUrl('/admin/?' . http_build_query(array(
            'callback' => $this->getCurrentUrl()
        )));
    }
}
