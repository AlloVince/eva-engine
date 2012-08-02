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
        $this->layout('layout/adminindex');
        $model->setTemplate('core/index');
        return $this->redirect()->toUrl('/admin/');
    }
}
