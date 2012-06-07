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
		$request = $this->getRequest();
		$page = $request->query()->get('page', 1);

		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$posts = $postTable->order('id DESC')->limit(10)->page($page)->find('all');
		p($postTable->debug());
        return array(
			'posts' => $posts->toArray()
		);
	}
}
