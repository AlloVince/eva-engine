<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Core\Auth;

class LogoutController extends ActionController
{
    public function indexAction()
    {
        $model = new ViewModel();
        $this->layout('layout/adminblank');
        $model->setTemplate('core/index');
        $auth = new Auth('Config', 'Session', 'Auth_Admin');
        $auth->getAuthStorage()->clear();
        return $this->redirect()->toUrl('/admin/');
    }
}
