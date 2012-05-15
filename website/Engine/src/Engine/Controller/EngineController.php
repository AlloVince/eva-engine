<?php
namespace Engine\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class EngineController extends ActionController
{
	protected $addResources = array(
	);

	public function indexAction()
	{
		p("website index");
	}

	public function restGetEngine()
	{
	}
}
