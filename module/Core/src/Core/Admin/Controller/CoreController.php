<?php
namespace Core\Admin\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class CoreController extends ActionController
{
	public function indexAction()
	{
		$this->layout('layout/admin'); 
        $model = new ViewModel(array(
		));
		$model->setTemplate('admin/index');
		return $model;
	}

	public function getAction()
	{
        $model = new ViewModel(array(
		));
		return $model;
	}
}
