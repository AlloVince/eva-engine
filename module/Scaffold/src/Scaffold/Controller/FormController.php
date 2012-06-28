<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class FormController extends ActionController
{
    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }
}
