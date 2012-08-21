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
        $posts = $postModel->setItemListParams(array('page' => $page))->getPosts();
        $postModel->cache();
        $paginator = $postModel->getPaginator();

        $this->pagecapture('abc');
        return array(
            'posts' => $posts,
            'paginator' => $paginator,
        );
    }
}
