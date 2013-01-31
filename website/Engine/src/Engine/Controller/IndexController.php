<?php
namespace Engine\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class IndexController extends ActionController
{
    public function indexAction()
    {
        //$this->pagecapture('index');
        $view = new ViewModel();
        return $view;
    }
}
