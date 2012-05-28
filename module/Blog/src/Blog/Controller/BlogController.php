<?php
namespace Blog\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{
	protected $addResources = array(
		'page',	
	);

	protected $renders = array(
		'getPage' => 'page2',	
	);

	public function restIndexBlog()
	{
	}

	public function restGetBlog()
	{
	}
}
