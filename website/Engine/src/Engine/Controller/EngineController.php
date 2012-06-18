<?php
namespace Engine\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class EngineController extends ActionController
{
    protected $addResources = array(
    );

    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTemplate('engine/index');
        return $view;
    }
}
