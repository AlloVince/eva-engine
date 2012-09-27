<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class PagesController extends RestfulModuleController
{

    public function indexAction()
    {
        $id = $this->params('id');
        $postModel = Api::_()->getModel('Blog\Model\Post');
        $items = $postModel->getPostList();
        //$this->pagecapture();
        return array(
            'items' => $items,
        );
    }

    public function getAction()
    {
        $id = $this->params('id');
        $postModel = Api::_()->getModel('Blog\Model\Post');
        $item = $postModel->getPost($id);
        //$this->pagecapture();
        return array(
            'item' => $item,
        );
    }

}
