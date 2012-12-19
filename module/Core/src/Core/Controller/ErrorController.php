<?php

namespace Core\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;

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
        $request = $this->getRequest();
        if($request instanceof ConsoleRequest){
            return 'command error';
        }
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
    }
}
