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
        $posts = $postModel->getPosts();
        $postModel->cache();
        $paginator = $postModel->getPaginator();

        return array(
            'posts' => $posts->toArray(),
            'paginator' => $paginator,
        );
    }
}
