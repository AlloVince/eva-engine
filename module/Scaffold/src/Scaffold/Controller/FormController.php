<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class FormController extends ActionController
{
    public function indexAction()
    {
        $view = new ViewModel();
        $view->setVariables(array('foo' => 'bar'));
        $view->setTemplate('index/index');
        return $view;
    }
}
