<?php
namespace Blog\Admin\Controller;

use Blog\Form,
	Eva\Api,
	Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{
	protected $renders = array(
		'restPutBlog' => 'blog/get',	
		'restPostBlog' => 'blog/get',	
		'restDeleteBlog' => 'remove/get',	
	);

	public function restIndexBlog()
	{
		$request = $this->getRequest();
		$page = $request->query()->get('page', 1);

		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$posts = $postTable->enableCount()->order('id DESC')->page($page)->find('all');
		$postCount = $postTable->getCount();
		//$paginator = $postTable->getPaginator();

        return array(
			'posts' => $posts->toArray()
		);
	}

	public function restGetBlog()
	{
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$postinfo = $postTable->find($id);
		return array(
			'post' => $postinfo,
		);
	}

	public function restPostBlog()
	{
		$request = $this->getRequest();
		$postData = $request->post();
		$form = new Form\PostForm();

		$form->enableFilters()->setData($postData);
		if ($form->isValid()) {

			$postData = $form->getData();

			$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
			$postData = $form->fieldsMap($postData, true);
			$postTable->create($postData);

		} else {
			
			//p($form->getInputFilter()->getInvalidInput());
		}

		return array(
			'form' => $form,
			'post' => $postData,
		);
	}

	public function restPutBlog()
	{
		$request = $this->getRequest();
		$postData = $request->post();
		$form = new Form\PostForm();
		$form->enableFilters()->setData($postData);
		if ($form->isValid()) {

			$postData = $form->getData();
			$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
			$postData = $form->fieldsMap($postData, true);
			$postTable->where("id = {$postData['id']}")->save($postData);

		} else {
		}

		return array(
			'form' => $form,
			'post' => $postData,
		);
	}

	public function restDeleteBlog()
	{
		$request = $this->getRequest();
		$postData = $request->post();
		$form = new Form\PostDeleteForm();
		$form->enableFilters()->setData($postData);
		if ($form->isValid()) {

			$postData = $form->getData();
			$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');

			$postTable->where("id = {$postData['id']}")->remove();

		} else {
			return array(
				'post' => $postData,
			);
		}
	}
}
