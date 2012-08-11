<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class LogoutController extends ActionController
{
    public function indexAction()
    {
        $model = new ViewModel();
        $this->layout('layout/adminblank');
        $model->setTemplate('core/index');
        $auth = new \Core\Auth();
        $auth->getStorage()->clear();
        return $this->redirect()->toUrl('/admin/');
    }
}
