<?php
namespace File\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FileController extends AbstractActionController
{
    public function uploadAction()
    {

        $view = new ViewModel();
        $view->setTemplate('blank');
        return $view;
    }
}
