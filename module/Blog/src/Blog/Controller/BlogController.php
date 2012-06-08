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
		//$keyword = 'a';
		//$posts = $postTable->order('id DESC')->find();

		/*
		$posts = $postTable->where(function($where) use ($keyword){
			$where->lessThan('id', 10);
			$where->greaterThan('id', 5);
			return $where;
		})->order('id DESC')->limit(10)->page($page);
		 */

		/*
		$posts = $postTable->where(
			array('id > 30')
		)->where(
			array('id < 10'), \Zend\Db\Sql\Where::OP_OR
		);
		 */

		// ( id < 10 or id > 20 ) and ( title = a or title = b )
		/*
		$posts = $postTable->where(function($where){
			$subWhereForId = clone $where;
			$subWhereForTitle = clone $where;

			$subWhereForId->lessThan('id', 10);
			$subWhereForId->or;
			$subWhereForId->greaterThan('id', 20);

			$where->addPredicate($subWhereForId);

			$subWhereForTitle->equalTo('title', 'a');
			$subWhereForTitle->or;
			$subWhereForTitle->equalTo('title', 'b');
			$where->addPredicate($subWhereForTitle);

			return $where;
		});
		 */
		$posts = $postTable->find('all');


		//p($posts);
		//->find();
		
		//p($postTable->debug());
        return array(
			'posts' => $posts
		);
	}
}
