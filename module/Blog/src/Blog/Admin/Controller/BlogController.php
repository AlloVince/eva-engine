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

		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$posts = $postTable->order('id DESC')->find('all');

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

	public function restPutBlog()
	{

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new \Blog\Form\PostForm();
			$form->enableFilters()->setData($request->post());
            if ($form->isValid()) {
				//p(1);
			} else {
				//p(2);
			}
		}

		return array(
			'form' => $form,
			'post' => $request->post(),
		);
	
	}
}
