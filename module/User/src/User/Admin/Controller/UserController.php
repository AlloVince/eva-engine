<?php
namespace User\Admin\Controller;

use Eva\Api,
	Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class UserController extends RestfulModuleController
{
	protected $renders = array(
		'restPutUser' => 'user/get',	
	);

	public function restIndexUser()
	{
		$this->layout('layout/admin'); 

		$postTable = Api::_()->getDbTable('User\DbTable\Users');
		$posts = $postTable->fetchAll();

        return array(
			'posts' => $posts->toArray()
		);
	}

	public function restGetUser()
	{
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
		$this->layout('layout/admin'); 
		$postTable = Api::_()->getDbTable('User\DbTable\Users');
		$postinfo = $postTable->getPost($id);
		return array(
			//'form' => $form,
			'post' => $postinfo,
		);
	}

	public function restPutUser()
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
