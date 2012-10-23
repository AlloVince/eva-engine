<?php
namespace User\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Core\Auth;

class LogoutController extends ActionController
{
    public function indexAction()
    {
        $callback = $this->params()->fromQuery('callback');
        if(!$callback && $this->getRequest()->getServer('HTTP_REFERER')){
            $callback = $this->getRequest()->getServer('HTTP_REFERER');
        }
        $callback = $callback ? $callback : '/';
        $model = new ViewModel();
        $auth = Auth::factory();
        $auth->getAuthStorage()->clear();
        $this->cookie()->clear('realm');
        return $this->redirect()->toUrl($callback);
    }
}
