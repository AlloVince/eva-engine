<?php
namespace Blog\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class PostController extends RestfulModuleController
{
	protected $addResources = array(
		'page',	
	);

	public function restIndexPost()
	{
		p(__METHOD__);exit;
	}

	public function restGetPost()
	{
		p(__METHOD__);exit;
	}

	public function restGetPostPage()
	{
		p(__METHOD__);exit;
	}
}
