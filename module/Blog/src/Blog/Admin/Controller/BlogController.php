<?php
namespace Blog\Admin\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{

	public function restIndexBlog()
	{
		$this->layout('layout/admin'); 
        $model = new ViewModel(array(
		));

		$postTable = new \Blog\DbTable\Posts();
		return $model;
	}

	/*
	public function indexAction()
	{
		$this->layout('layout/admin'); 
        $model = new ViewModel(array(
		));
		return $model;
	}
	 */
}
