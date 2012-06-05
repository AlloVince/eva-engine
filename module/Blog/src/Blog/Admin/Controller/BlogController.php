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

	public function restGetBlog()
	{
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		$this->layout('layout/admin'); 
		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$postinfo = $postTable->getPost($id);
		return array(
			//'form' => $form,
			'post' => $postinfo,
		);
	}
}
