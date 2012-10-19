<?php
namespace File\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;

class FileController extends RestfulModuleController
{
    public function uploadAction()
    {
        $view = new ViewModel();
        $view->setTemplate('blank');
        return $view;
    }

    public function restIndexFile()
    {
    
    }

    public function restDeleteFile()
    {
        $id = $this->params('id');
        $view = new ViewModel();
        $view->setTemplate('blank');
        return $view;
    }
}
