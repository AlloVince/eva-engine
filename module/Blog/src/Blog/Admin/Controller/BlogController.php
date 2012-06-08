<?php
namespace Blog\Admin\Controller;

use Eva\Api,
	Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{
	protected $renders = array(
		'restPutBlog' => 'blog/get',	
	);

	public function restIndexBlog()
	{
		$this->layout('layout/admin'); 
		$request = $this->getRequest();
		$page = $request->query()->get('page', 1);

		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$posts = $postTable->order('id DESC')->page(1)->find('all');

        return array(
			'posts' => $posts->toArray()
		);
	}

	public function restGetBlog()
	{
		$this->layout('layout/admin'); 
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$postinfo = $postTable->find($id);
		return array(
			'post' => $postinfo,
		);
	}

	public function restPutBlog()
	{
        $request = $this->getRequest();
		if ($request->isPost()) {
			$postData = $request->post();
            $form = new \Blog\Form\PostForm();
			$form->enableFilters()->setData($postData);
            if ($form->isValid()) {

			} else {
			}
		}

		return array(
			'form' => $form,
			'post' => $request->post(),
		);
	
	}
}
