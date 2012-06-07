<?php
namespace Blog\Controller;

use Eva\Api,
	Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{
	protected $addResources = array(
		'page',	
	);

	public function restIndexBlog()
	{
		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$posts = $postTable->fetchAll();

        return array(
			'posts' => $posts->toArray()
		);
	}
}
