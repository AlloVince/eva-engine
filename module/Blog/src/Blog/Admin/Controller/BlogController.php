<?php
namespace Blog\Admin\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class BlogController extends ActionController
{
	public function indexAction()
	{
		$this->layout('layout/admin'); 
        $model = new ViewModel(array(
		));
		$model->setTemplate('blog/index');
		return $model;
	}
}
