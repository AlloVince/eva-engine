<?php
namespace User\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class UserController extends RestfulModuleController
{
	protected $addResources = array(
	);

	public function restIndexUser()
	{
	}
}
