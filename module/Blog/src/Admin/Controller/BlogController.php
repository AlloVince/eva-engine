<?php
namespace Blog\Admin\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class BlogController extends ActionController
{
	public function indexAction()
	{
		p('abcde');
        $model = new ViewModel(array(
		));
		return $model;
	}
}
