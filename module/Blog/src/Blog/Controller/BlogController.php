<?php
namespace Blog\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{
	protected $postTable;

	protected $addResources = array(
		'page',	
	);

	protected $renders = array(
		'getPage' => 'page2',	
	);

    public function setPostTable(PostTable $postTable)
    {
        $this->postTable = $postTable;
        return $this;
	}  

	public function restIndexBlog()
	{
	}

	public function restGetBlog()
	{
        $form = new \Blog\Form\BlogForm();
        return array('form' => $form);
	}
}
