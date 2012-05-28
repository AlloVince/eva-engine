<?php
namespace Blog\Admin\Controller;

use Eva\Api,
	Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{

	public function restIndexBlog()
	{
		$this->layout('layout/admin'); 

		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$posts = $postTable->fetchAll();

        return array(
			'posts' => $posts->toArray()
		);
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
