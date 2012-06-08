<?php
namespace Blog\Controller;

use Eva\Api,
	Eva\Mvc\Controller\RestfulModuleController,
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
        $id = $this->getEvent()->getRouteMatch()->getParam('id');


		$postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
		$postinfo = $postTable->find($id);
		//p($postTable->debug());
		//p($postinfo);
		return array(
			//'form' => $form,
			'post' => $postinfo,
		);
	}

	public function restGetPostPage()
	{
		p(__METHOD__);exit;
	}
}
