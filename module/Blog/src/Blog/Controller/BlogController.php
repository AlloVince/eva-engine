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
        $page = $request->getQuery()->get('page', 1);

        $postModel = Api::_()->getModel('Blog\Model\Post');
        $postTable = $postModel->getItemTable();
        $posts = $postTable->enableCount()->order('id DESC')->page($page)->find('all');
        $paginator = $postModel->getPaginator();

        return array(
            'posts' => $posts->toArray(),
            'paginator' => $paginator,
        );
    }
}
