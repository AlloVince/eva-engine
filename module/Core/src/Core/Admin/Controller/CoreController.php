<?php
namespace Core\Admin\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class CoreController extends ActionController
{
	public function indexAction()
	{
		p('admin/index');
        $model = new ViewModel(array(
		));
		return $model;
	}

	public function getAction()
	{
		p('admin/get');
        $model = new ViewModel(array(
		));
		return $model;
	}
}
